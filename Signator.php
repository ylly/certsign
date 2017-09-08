<?php

namespace Ylly\CertiSign;

use Ylly\CertiSign\Client\SignClient;
use Ylly\CertiSign\Client\SMSClient;
use Ylly\CertiSign\Data\Document;
use Ylly\CertiSign\Data\Request;
use Ylly\CertiSign\Data\Signature;

class Signator
{
    private $signClient;
    private $smsClient;
    private $domain;

    public function __construct($environnement, $certPath, $certPassword, $apiKey, $domain)
    {
        $this->signClient = new SignClient($environnement, $certPath, $certPassword);
        $this->smsClient = new SMSClient($environnement, $apiKey);
        $this->domain = $domain;
    }

    public function sendAuthentificationRequest($number)
    {
        $response = $this->smsClient->call('AddAcces', [
            'indicatifRegional' => '33',
            'identifiant' => $number,
            'url' => $this->domain,
            'parametres' => ''
        ]);

        return isset($response->error) && $response->error == 0;
    }

    public function checkAuthentificationRequest($number, $code)
    {
        $response = $this->smsClient->call('CheckAcces', [
            'indicatifRegional' => '33',
            'identifiant' => $number,
            'code' => $code
        ]);

        return isset($response->error) && $response->error == 0;
    }

    public function signDocuments(Request $request, Signature $signatureInfo)
    {
        $order = $this->createSignOrder($request);

        $signatures = $this->createSignRequest($request, $signatureInfo, $order->orderRequestId);

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

    private function createSignRequest(Request $request, Signature $signatureInfo, $orderId)
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
                    'signatureTextColor' => $signatureInfo->color,
                    'signatureTextFontSize' => $signatureInfo->size,
                    'fontFamily' => 'Courier',
                    'fontStyle' => 'Normal',
                    'signatureImageContent' => $signatureInfo->image,
                    'signatureText' => $signatureInfo->text,
                    'signaturePosX' => $signatureInfo->posX,
                    'signaturePosY' => $signatureInfo->posY,
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