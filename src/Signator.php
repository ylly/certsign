<?php

namespace YllyCertiSign;

use YllyCertiSign\Client\SignClient;
use YllyCertiSign\Client\SMSClient;
use YllyCertiSign\Data\Document;
use YllyCertiSign\Data\Request;
use YllyCertiSign\Log\LogListenerInterface;

class Signator
{
    private $signClient;
    private $smsClient;
    private $domain;

    private function __construct($environnement, $certPath, $certPassword, $apiKey, $domain, $proxy)
    {
        $this->signClient = new SignClient($environnement, $certPath, $certPassword, $proxy);
        $this->smsClient = new SMSClient($environnement, $apiKey, $proxy);
        $this->domain = $domain;
    }

    public static function createFromYaml($config)
    {
        return new Signator(
            $config['env'],
            $config['cert'],
            $config['cert_password'],
            $config['api_key'],
            $config['api_endpoint'],
            isset($config['proxy']) ? $config['proxy'] : null
        );
    }

    public static function createFromYamlFile($pathToFile)
    {
        $config = Configurator::loadFromFile($pathToFile);
        return self::createFromYaml($config);
    }

    public function addListener(LogListenerInterface $listener)
    {
        $this->signClient->addListener($listener);
        $this->smsClient->addListener($listener);
    }

    public function sendAuthenticationRequest($number)
    {
        $response = $this->smsClient->call('AddAcces', [
            'indicatifRegional' => '33',
            'identifiant' => $number,
            'url' => $this->domain,
            'parametres' => ''
        ]);

        return isset($response->error) && $response->error == 0;
    }

    public function checkAuthenticationRequest($number, $code)
    {
        $response = $this->smsClient->call('CheckAcces', [
            'indicatifRegional' => '33',
            'identifiant' => $number,
            'code' => $code
        ]);

        return isset($response->error) && $response->error == 0;
    }

    public function signDocuments(Request $request)
    {
        $order = $this->createSignOrder($request);

        $signatures = $this->createSignRequest($request, $order->orderRequestId);

        $this->signRequest($order->orderRequestId);

        return $this->getSignedDocuments($signatures);
    }

    private function createSignOrder(Request $request)
    {
        $signatureOrderData = [
            'holder' => [
                'firstname' => $request->holder->firstname,
                'lastname' => $request->holder->lastname,
                'email' => $request->holder->email,
                'mobile' => $request->holder->mobile,
                'country' => 'FR'
            ]
        ];

        return $this->signClient->post('/ephemeral/orders', $signatureOrderData);
    }

    private function createSignRequest(Request $request, $orderId)
    {
        $signData = [];
        foreach($request->documents as $document) {
            $signData[] = [
                'externalSignatureRequestId' => $orderId . '_' . $document->name,
                'signatureOptions' => [
                    'signatureType' => 'PAdES_BASELINE_LTA',
                    'digestAlgorithmName' => 'SHA256',
                    'signaturePackagingType' => 'ENVELOPED',
                    'documentType' => 'INLINE'
                ],
                'pdfSignatureOptions' => [
                    'signatureTextColor' => $document->signature->color,
                    'signatureTextFontSize' => $document->signature->size,
                    'fontFamily' => 'Courier',
                    'fontStyle' => 'Normal',
                    'signatureImageContent' => $document->signature->image,
                    'signatureText' => $document->signature->text,
                    'signaturePosX' => $document->signature->posX,
                    'signaturePosY' => $document->signature->posY,
                    'signaturePage' => 1
                ],
                'toSignContent' => $document->data
            ];
        }

        return $this->signClient->post('/ephemeral/signatures?orderRequestId=' . $orderId, $signData);
    }

    private function signRequest($orderId)
    {
        return $this->signClient->post('/ephemeral/signatures/sign?mode=SYNC&orderRequestId=' . $orderId);
    }

    /**
     * @param $signatures
     * @return Document[]
     */
    private function getSignedDocuments($signatures)
    {
        $documents = [];

        foreach($signatures as $signature) {
            $signed = $this->signClient->get('/ephemeral/signatures/?id=' . $signature->signatureRequestId);
            $name = explode('_', $signed->externalSignatureRequestId)[1];
            $doc = new Document($name, $signed->signedContent);
            $documents[] = $doc;
        }

        return $documents;
    }
}