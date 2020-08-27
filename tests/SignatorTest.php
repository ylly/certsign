<?php

use PHPUnit\Framework\TestCase;
use YllyCertSign\Client\Sign\SignTestClient;
use YllyCertSign\Exception\WebserviceException;
use YllyCertSign\Request\Request;
use YllyCertSign\Request\Signature\Signature;
use YllyCertSign\Signator;

class SignatorTest extends TestCase
{
    /**
     * @dataProvider signProvider
     *
     * @param int|null $expectedCount
     * @param string|null $otp
     *
     * @throws WebserviceException
     */
    public function testCreateSignOrder($expectedCount, $otp)
    {
        $client = new SignTestClient();
        $signator = new Signator($client);

        $signature = new Signature();

        $document = __DIR__ . '/data/doc.pdf';
        $base64 = base64_encode(file_get_contents($document));

        $request = Request::create()
            ->setHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
            ->addDocument('DOC1', $document, $signature, false)
            ->addDocument('DOC2', $base64, $signature)
        ;

        $orderId = $signator->createOrder($request);
        $signator->createRequest($request, $orderId);

        if (null === $expectedCount) {
            $this->expectException('YllyCertSign\Exception\WebserviceException');
        }

        $documents = $signator->sign($orderId, $otp);

        if (null !== $expectedCount) {
            $this->assertCount($expectedCount, $documents);
        }
    }

    public function signProvider()
    {
        return [
            [2, null],
            [2, 'VALID_OTP'],
            [null, 'ERROR_OTP'],
            [null, 'INVALID_SIGNATURE'],
            [null, 'INVALID_SIGNATURE2'],
            [null, 'EMPTY_DOCUMENT'],
        ];
    }
}
