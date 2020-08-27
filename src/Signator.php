<?php

namespace YllyCertSign;

use YllyCertSign\Client\Sign\SignClientInterface;
use YllyCertSign\Exception\OTPException;
use YllyCertSign\Exception\WebserviceException;
use YllyCertSign\Log\LogListenerInterface;
use YllyCertSign\Request\Request;
use YllyCertSign\Request\Signature\Document;

class Signator
{
    const ERROR_WRONG_OTP = 'ERROR_MAUVAIS_CODE';
    const ERROR_ELAPSED_OTP = 'ERROR_NBR_ESSAI';
    const ERROR_KO_OTP = 'ERROR_CODE_KO';

    /**
     * @var SignClientInterface
     */
    private $client;

    public function __construct(SignClientInterface $client)
    {
        $this->client = $client;
    }

    public function addListener(LogListenerInterface $listener)
    {
        $this->client->addListener($listener);
    }

    /**
     * @throws WebserviceException
     *
     * @return int
     */
    public function createOrder(Request $request)
    {
        $response = $this->createSignOrder($request);

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        } elseif (!isset($response->orderRequestId)) {
            throw new WebserviceException();
        }

        return $response->orderRequestId;
    }

    /**
     * @param int $orderId
     * @param string|null $externalIdPrefix
     *
     * @throws WebserviceException
     */
    public function createRequest(Request $request, $orderId, $externalIdPrefix = null)
    {
        $response = $this->createSignRequest($request, $orderId, $externalIdPrefix);

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        }
    }

    /**
     * @param int $orderId
     *
     * @throws WebserviceException
     */
    public function validate($orderId)
    {
        $response = $this->validateRequest($orderId);

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        }
    }

    /**
     * @param int $orderId
     * @param string|null $otp
     *
     * @throws WebserviceException
     *
     * @return Document[]
     */
    public function sign($orderId, $otp = null)
    {
        $response = $this->signRequest($orderId, $otp);

        if (isset($response->errorMsg)) {
            $otpErrors = [self::ERROR_WRONG_OTP, self::ERROR_ELAPSED_OTP, self::ERROR_KO_OTP];
            if (isset($response->errorLabel) && in_array($response->errorLabel, $otpErrors)) {
                throw new OTPException($response->errorMsg);
            }
            throw new WebserviceException($response->errorMsg);
        } elseif (!is_array($response)) {
            throw new WebserviceException();
        }

        return $this->getSignedDocuments($response);
    }

    /**
     * @throws WebserviceException
     *
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
                'country' => $request->holder->country,
            ],
            'enableOtp' => $request->otp->enabled,
            'otpContact' => $request->otp->contact,
            'clientIdentifier' => $request->getClientId(),
        ];

        $response = $this->client->post('/ephemeral/orders', $signatureOrderData);
        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        }

        return $response;
    }

    /**
     * @param int $orderId
     * @param string $externalIdPrefix
     *
     * @throws WebserviceException
     *
     * @return object|array
     */
    private function createSignRequest(Request $request, $orderId, $externalIdPrefix)
    {
        $signData = [];

        foreach ($request->documents as $document) {
            $signData[] = [
                'externalSignatureRequestId' => $externalIdPrefix . $orderId . '_' . $document->name,
                'signatureOptions' => [
                    'signatureType' => 'PAdES_BASELINE_LTA',
                    'digestAlgorithmName' => 'SHA256',
                    'signaturePackagingType' => 'ENVELOPED',
                    'documentType' => 'INLINE',
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
                    'signaturePage' => $document->signature->page,
                ],
                'toSignContent' => $document->data,
            ];
        }

        if ($request->otp->enabled) {
            $response = $this->client->post('/ephemeral/trigger/signatures?orderRequestId=' . $orderId, $signData);
        } else {
            $response = $this->client->post('/ephemeral/signatures?orderRequestId=' . $orderId, $signData);
        }

        if (isset($response->errorMsg)) {
            throw new WebserviceException($response->errorMsg);
        }

        return $response;
    }

    /**
     * @param int $orderId
     *
     * @return object|array
     */
    private function validateRequest($orderId)
    {
        return $this->client->post('/ephemeral/trigger/signatures/validate?orderRequestId=' . $orderId);
    }

    /**
     * @param int $orderId
     * @param string $otp
     *
     * @return object|array
     */
    private function signRequest($orderId, $otp)
    {
        if (!empty($otp)) {
            return $this->client->post('/ephemeral/trigger/signatures/sign?mode=SYNC&orderRequestId=' . $orderId . '&otp=' . $otp);
        }

        return $this->client->post('/ephemeral/signatures/sign?mode=SYNC&orderRequestId=' . $orderId);
    }

    /**
     * @param object[] $signatures
     *
     * @return Document[]
     */
    private function getSignedDocuments($signatures)
    {
        $documents = [];

        foreach ($signatures as $signature) {
            $signed = $this->client->get('/ephemeral/signatures/?id=' . $signature->signatureRequestId);
            $name = explode('_', $signed->externalSignatureRequestId)[1];
            $doc = new Document($name, $signed->signedContent);
            $documents[] = $doc;
        }

        return $documents;
    }
}
