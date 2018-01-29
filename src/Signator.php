<?php

namespace YllyCertiSign;

use YllyCertiSign\Client\Sign\SignClientInterface;
use YllyCertiSign\Client\SMS\SMSClientInterface;
use YllyCertiSign\Data\Document;
use YllyCertiSign\Data\Request;
use YllyCertiSign\Log\LogListenerInterface;

class Signator
{
    /** @var SignClientInterface */
    private $signClient;

    /** @var SMSClientInterface */
    private $smsClient;

    /** @var string */
    private $domain;

    /**
     * @param SignClientInterface $signClient
     * @param SMSClientInterface $smsClient
     * @param string $domain
     */
    public function __construct(SignClientInterface $signClient, SMSClientInterface $smsClient, $domain)
    {
        $this->signClient = $signClient;
        $this->smsClient = $smsClient;
        $this->domain = $domain;
    }

    /**
     * @param LogListenerInterface $listener
     */
    public function addListener(LogListenerInterface $listener)
    {
        $this->signClient->addListener($listener);
        $this->smsClient->addListener($listener);
    }

    /**
     * @param string $number
     * @return bool
     */
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

    /**
     * @param string $number
     * @param string $code
     * @return bool
     */
    public function checkAuthenticationRequest($number, $code)
    {
        $response = $this->smsClient->call('CheckAcces', [
            'indicatifRegional' => '33',
            'identifiant' => $number,
            'code' => $code
        ]);

        return isset($response->error) && $response->error == 0;
    }

    /**
     * @param Request $request
     * @return Data\Document[]
     */
    public function signDocuments(Request $request)
    {
        $order = $this->createSignOrder($request);

        $signatures = $this->createSignRequest($request, $order->orderRequestId);

        $this->signRequest($order->orderRequestId);

        return $this->getSignedDocuments($signatures);
    }

    /**
     * @param Request $request
     * @return object|array
     */
    private function createSignOrder(Request $request)
    {
        $signatureOrderData = [
            'holder' => [
                'firstname' => $request->holder->firstname,
                'lastname' => $request->holder->lastname,
                'email' => $request->holder->email,
                'mobile' => $request->holder->mobile,
                'country' => $request->holder->country
            ]
        ];

        return $this->signClient->post('/ephemeral/orders', $signatureOrderData);
    }

    /**
     * @param Request $request
     * @param int $orderId
     * @return object|array
     */
    private function createSignRequest(Request $request, $orderId)
    {
        $signData = [];
        foreach ($request->documents as $document) {
            $signData[] = [
                'externalSignatureRequestId' => $orderId . '_' . $document->name,
                'signatureOptions' => [
                    'signatureType' => 'PAdES_BASELINE_LTA',
                    'digestAlgorithmName' => 'SHA256',
                    'signaturePackagingType' => 'ENVELOPED',
                    'documentType' => 'INLINE'
                ],
                'pdfSignatureOptions' => [
                    'signatureTextColor' => '0000',
                    'signatureTextFontSize' => '10',
                    'fontFamily' => 'Courier',
                    'fontStyle' => 'Normal',
                    'signatureText' => '',
                    'signatureImageContent' => $document->signature->image,
                    'signaturePosX' => $document->signature->posX,
                    'signaturePosY' => $document->signature->posY,
                    'signaturePage' => $document->signature->page
                ],
                'toSignContent' => $document->data
            ];
        }

        return $this->signClient->post('/ephemeral/signatures?orderRequestId=' . $orderId, $signData);
    }

    /**
     * @param int $orderId
     * @return object|array
     */
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

        foreach ($signatures as $signature) {
            $signed = $this->signClient->get('/ephemeral/signatures/?id=' . $signature->signatureRequestId);
            $name = explode('_', $signed->externalSignatureRequestId)[1];
            $doc = new Document($name, $signed->signedContent);
            $documents[] = $doc;
        }

        return $documents;
    }
}
