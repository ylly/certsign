<?php

namespace YllyCertiSign\Client\Sign;

use YllyCertiSign\Client\AbstractClient;

class SignTestClient extends AbstractClient implements SignClientInterface
{
    public function get($url)
    {
        if (strpos($url, '/ephemeral/signatures/?id=') !== false) {
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

    public function post($url, $content = [])
    {
        if (strpos($url, '/ephemeral/orders') !== false) {
            return (object)[
                'orderRequestId' => 1234,
                'externalOrderRequestId' => null,
                'status' => 'VALIDATED'
            ];
        } elseif (strpos($url, '/ephemeral/signatures?orderRequestId=') !== false) {
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
        } elseif (strpos($url, '/ephemeral/signatures/sign?mode=SYNC&orderRequestId=') !== false) {
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
