<?php

use YllyCertSign\Client\Sign\SignTestClient;
use YllyCertSign\Data\Request;
use YllyCertSign\Data\Signature;
use YllyCertSign\Signator;

class SignatorTest extends \PHPUnit\Framework\TestCase
{
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
            ->addDocument('DOC2', $base64, $signature);

        $orderId = $signator->create($request);
        $documents = $signator->sign($orderId);

        $this->assertEquals(2, count($documents));
    }

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
            ->addDocument('DOC2', $base64, $signature);

        $orderId = $signator->create($request);
        $signator->validate($orderId);
        $documents = $signator->sign($orderId, '1234');

        $this->assertEquals(2, count($documents));
    }
}
