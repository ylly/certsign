<?php

namespace YllyCertSign\Client\Sign;

use YllyCertSign\Client\AbstractClient;

class SignTestClient extends AbstractClient implements SignClientInterface
{
    /**
     * @param string $url
     * @return object
     * @throws \Exception
     */
    public function get($url)
    {
        if (strpos($url, '/signatures/?id=') !== false) {
            return (object)[
                'signatureRequestId' => 1,
                'externalSignatureRequestId' => '1234_DOC1',
                'orderRequestId' => 1234,
                'externalOrderRequestId' => null,
                'status' => 'SIGNED',
                'signedContent' => ''
            ];
        } else {
            throw new \Exception('Call on undefined API method');
        }
    }

    /**
     * @param string $url
     * @param array $content
     * @return array|null|object
     * @throws \Exception
     */
    public function post($url, $content = [])
    {
        if (strpos($url, '/orders') !== false) {
            return (object)[
                'orderRequestId' => 1234,
                'externalOrderRequestId' => null,
                'status' => 'VALIDATED'
            ];
        } elseif (strpos($url, '/signatures?orderRequestId=') !== false) {
            return [
                (object)[
                    'signatureRequestId' => 1,
                    'externalSignatureRequestId' => '1234_DOC1',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGN_CREATED'
                ],
                (object)[
                    'signatureRequestId' => 2,
                    'externalSignatureRequestId' => '1234_DOC2',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGN_CREATED'
                ]
            ];
        } elseif (strpos($url, '/signatures/validate?orderRequestId=') !== false) {
            return null;
        } elseif (strpos($url, '/signatures/sign?mode=SYNC&orderRequestId=') !== false) {
            return [
                (object)[
                    'signatureRequestId' => 1,
                    'externalSignatureRequestId' => '1234_DOC1',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGNED'
                ],
                (object)[
                    'signatureRequestId' => 2,
                    'externalSignatureRequestId' => '1234_DOC2',
                    'orderRequestId' => 1234,
                    'externalOrderRequestId' => null,
                    'status' => 'SIGNED'
                ]
            ];
        } else {
            throw new \Exception('Call on undefined API method');
        }
    }
}
