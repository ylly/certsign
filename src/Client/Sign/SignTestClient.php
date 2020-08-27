<?php

namespace YllyCertSign\Client\Sign;

use BadFunctionCallException;
use YllyCertSign\Client\AbstractClient;
use YllyCertSign\Signator;

class SignTestClient extends AbstractClient implements SignClientInterface
{
    /**
     * @param string $url
     *
     * @return object
     */
    public function get($url)
    {
        if (false !== strpos($url, '/signatures/?id=')) {
            $id = explode('?id=', $url);
            $id = isset($id[1]) ? $id[1] : null;

            switch ($id) {
                case 1:
                case 2:
                    return (object)[
                        'signatureRequestId' => 1,
                        'externalSignatureRequestId' => '1234_DOC1',
                        'orderRequestId' => 1234,
                        'externalOrderRequestId' => null,
                        'status' => 'SIGNED',
                        'signedContent' => 'base64',
                    ];
                case 99:
                    return (object)[
                        'signatureRequestId' => 1,
                        'externalSignatureRequestId' => '1234_DOC1',
                        'orderRequestId' => 1234,
                        'externalOrderRequestId' => null,
                        'status' => 'SIGN_FAILED',
                        'signedContent' => '',
                    ];
                case 999:
                    return (object)[
                        'signatureRequestId' => 1,
                        'externalSignatureRequestId' => '1234_DOC1',
                        'orderRequestId' => 1234,
                        'externalOrderRequestId' => null,
                        'status' => 'SIGNED',
                        'signedContent' => '',
                    ];
            }
        }

        throw new BadFunctionCallException('Call on undefined API method');
    }

    /**
     * @param string $url
     * @param array $content
     *
     * @return array|object|null
     */
    public function post($url, $content = [])
    {
        if (false !== strpos($url, '/orders')) {
            return (object)[
                'orderRequestId' => 1234,
                'externalOrderRequestId' => null,
                'status' => 'VALIDATED',
            ];
        } elseif (false !== strpos($url, '/signatures?orderRequestId=')) {
            return [
                (object)[
                    'signatureRequestId' => 1,
                    'externalSignatureRequestId' => '1234_DOC1',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGN_CREATED',
                ],
                (object)[
                    'signatureRequestId' => 2,
                    'externalSignatureRequestId' => '1234_DOC2',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGN_CREATED',
                ],
            ];
        } elseif (false !== strpos($url, '/signatures/validate?orderRequestId=')) {
            return null;
        } elseif (false !== strpos($url, '/signatures/sign?mode=SYNC&orderRequestId=')) {
            $otp = explode('&otp=', $url);
            $otp = isset($otp[1]) ? $otp[1] : null;

            switch ($otp) {
                case null:
                case 'VALID_OTP':
                    return [
                        (object)[
                            'signatureRequestId' => 1,
                            'externalSignatureRequestId' => '1234_DOC1',
                            'orderRequestId' => 1234,
                            'externalOrderRequestId' => null,
                            'status' => 'SIGNED',
                        ],
                        (object)[
                            'signatureRequestId' => 2,
                            'externalSignatureRequestId' => '1234_DOC2',
                            'orderRequestId' => 1234,
                            'externalOrderRequestId' => null,
                            'status' => 'SIGNED',
                        ],
                    ];
                case 'ERROR_OTP':
                    return (object)[
                        'errorMsg' => Signator::ERROR_KO_OTP,
                        'errorLabel' => Signator::ERROR_KO_OTP,
                    ];
                case 'INVALID_SIGNATURE':
                    return [
                        (object)[
                            'signatureRequestId' => 1,
                            'externalSignatureRequestId' => '1234_DOC1',
                            'orderRequestId' => 1234,
                            'externalOrderRequestId' => null,
                            'status' => 'SIGN_FAILED',
                        ],
                    ];
                case 'INVALID_SIGNATURE2':
                    return [
                        (object)[
                            'signatureRequestId' => 99,
                            'externalSignatureRequestId' => '1234_DOC1',
                            'orderRequestId' => 1234,
                            'externalOrderRequestId' => null,
                            'status' => 'SIGNED',
                        ],
                    ];
                case 'EMPTY_DOCUMENT':
                    return [
                        (object)[
                            'signatureRequestId' => 999,
                            'externalSignatureRequestId' => '1234_DOC1',
                            'orderRequestId' => 1234,
                            'externalOrderRequestId' => null,
                            'status' => 'SIGNED',
                        ],
                    ];
            }
        }

        throw new BadFunctionCallException('Call on undefined API method');
    }
}
