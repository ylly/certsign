<?php

use YllyCertiSign\Configurator;
use YllyCertiSign\Data\Request;
use YllyCertiSign\Data\Signature;
use YllyCertiSign\Signator;

class SignatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var Signator */
    private static $signator;

    public static function setUpBeforeClass()
    {
        self::$signator = Signator::createFromYamlFile(__DIR__ . '/config.yml');
    }

    public function testSendSMS()
    {
        $config = Configurator::loadFromFile(__DIR__ . '/config.yml');

        $sent = self::$signator->sendAuthenticationRequest($config['sms_destination']);
        $this->assertTrue($sent);

        $validated = self::$signator->checkAuthenticationRequest($config['sms_destination'], '000000');
        $this->assertFalse($validated);
    }

    public function testCreateSignOrder()
    {
        $path = __DIR__ . '/data/';

        $signature = Signature::create()->setImage($path . 'sign.png', false)->setText('Signature');

        $document = $path . 'doc.pdf';
        $base64 = base64_encode(file_get_contents($document));

        $request = Request::create()
            ->addHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
            ->addDocument('Test-1', $document, $signature, false)
            ->addDocument('Test-2', $base64, $signature);

        $documents = self::$signator->signDocuments($request);

        $this->assertEquals(2, count($documents));
    }
}