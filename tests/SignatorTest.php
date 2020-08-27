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
     * @throws WebserviceException
     */
    public function testCreateSignOrder()
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
        $documents = $signator->sign($orderId);

        $this->assertCount(2, $documents);
    }

    /**
     * @throws WebserviceException
     */
    public function testCreateOTPSignOrder()
    {
        $client = new SignTestClient();
        $signator = new Signator($client);

        $signature = new Signature();

        $document = __DIR__ . '/data/doc.pdf';
        $base64 = base64_encode(file_get_contents($document));

        $request = Request::create()
            ->setHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
            ->setOTP('0601020304')
            ->addDocument('DOC1', $document, $signature, false)
            ->addDocument('DOC2', $base64, $signature)
        ;

        $orderId = $signator->createOrder($request);
        $signator->validate($orderId);
        $signator->createRequest($request, $orderId);
        $documents = $signator->sign($orderId, '1234');

        $this->assertCount(2, $documents);
    }
}
