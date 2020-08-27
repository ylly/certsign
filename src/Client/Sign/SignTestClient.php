<?php

namespace YllyCertSign\Client\Sign;

use BadFunctionCallException;
use YllyCertSign\Client\AbstractClient;

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
            return (object)[
                'signatureRequestId' => 1,
                'externalSignatureRequestId' => '1234_DOC1',
                'orderRequestId' => 1234,
                'externalOrderRequestId' => null,
                'status' => 'SIGNED',
                'signedContent' => '',
            ];
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
        }

        throw new BadFunctionCallException('Call on undefined API method');
    }
}
