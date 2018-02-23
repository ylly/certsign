<?php

namespace YllyCertSign;

use YllyCertiSign\Exception\WebserviceException;
use YllyCertSign\Client\Sign\SignClientInterface;
use YllyCertSign\Log\LogListenerInterface;
use YllyCertSign\Request\Request;
use YllyCertSign\Request\Signature\Document;

class Signator
{
    /**
     * @var SignClientInterface
     */
    private $client;

    /**
     * @param SignClientInterface $client
     */
    public function __construct(SignClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param LogListenerInterface $listener
     */
    public function addListener(LogListenerInterface $listener)
    {
        $this->client->addListener($listener);
    }

    /**
     * @param Request $request
     * @return int
     * @throws WebserviceException
     */
    public function createOrder(Request $request)
    {
        $response = $this->createSignOrder($request);

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        } else {
            return $response->orderRequestId;
        }
    }

    /**
     * @param Request $request
     * @param int $orderId
     * @throws WebserviceException
     */
    public function createRequest(Request $request, $orderId)
    {
        $response = $this->createSignRequest($request, $orderId);

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        }
    }

    /**
     * @param int $orderId
     */
    public function validate($orderId)
    {
        $this->validateRequest($orderId);
    }

    /**
     * @param int $orderId
     * @param string|null $otp
     * @return Document[]
     * @throws WebserviceException
     */
    public function sign($orderId, $otp = null)
    {
        $response = $this->signRequest($orderId, $otp);
        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        } else {
            return $this->getSignedDocuments($response);
        }
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
            ],
            'enableOtp' => $request->otp->enabled,
            'otpContact' => $request->otp->contact
        ];

        return $this->client->post('/ephemeral/orders', $signatureOrderData);
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

        if ($request->otp->enabled) {
            return $this->client->post('/ephemeral/trigger/signatures?orderRequestId=' . $orderId, $signData);
        } else {
            return $this->client->post('/ephemeral/signatures?orderRequestId=' . $orderId, $signData);
        }
    }

    /**
     * @param int $orderId
     * @return object|array
     */
    private function validateRequest($orderId)
    {
        return $this->client->post('/ephemeral/trigger/signatures/validate?orderRequestId=' . $orderId);
    }

    /**
     * @param int $orderId
     * @param string $otp
     * @return object|array
     */
    private function signRequest($orderId, $otp)
    {
        if (!empty($otp)) {
            return $this->client->post('/ephemeral/trigger/signatures/sign?mode=SYNC&orderRequestId=' . $orderId . '&otp=' . $otp);
        } else {
            return $this->client->post('/ephemeral/signatures/sign?mode=SYNC&orderRequestId=' . $orderId);
        }
    }

    /**
     * @param $signatures
     * @return Document[]
     */
    private function getSignedDocuments($signatures)
    {
        $documents = [];

        foreach ($signatures as $signature) {
            if (isset($signature->signatureRequestId)) {
                $signed = $this->client->get('/ephemeral/signatures/?id=' . $signature->signatureRequestId);
                $name = explode('_', $signed->externalSignatureRequestId)[1];
                $doc = new Document($name, $signed->signedContent);
                $documents[] = $doc;
            }
        }

        return $documents;
    }
}
