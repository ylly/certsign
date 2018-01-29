<?php

use YllyCertSign\Client\Sign\SignTestClient;
use YllyCertSign\Client\SMS\SMSTestClient;
use YllyCertSign\Data\Request;
use YllyCertSign\Data\Signature;
use YllyCertSign\Signator;

class SignatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var Signator */
    private static $signator;

    public static function setUpBeforeClass()
    {
        $signClient = new SignTestClient();
        $smsClient = new SMSTestClient();
        self::$signator = new Signator($signClient, $smsClient, '');
    }

    public function testSendAndValidateSMS()
    {
        $sent = self::$signator->sendAuthenticationRequest('0601020304');
        $this->assertTrue($sent);

        $validated = self::$signator->checkAuthenticationRequest('0601020304', '123456');
        $this->assertTrue($validated);
    }

    public function testCreateSignOrder()
    {
        $signature = new Signature();

        $document = __DIR__ . '/data/doc.pdf';
        $base64 = base64_encode(file_get_contents($document));

        $request = Request::create()
            ->setHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
            ->addDocument('DOC1', $document, $signature, false)
            ->addDocument('DOC2', $base64, $signature);

        $documents = self::$signator->signDocuments($request);

        $this->assertEquals(2, count($documents));
    }
}
