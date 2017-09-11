<?php

use YllyCertiSign\Data\Request;
use YllyCertiSign\Data\Signature;
use YllyCertiSign\Signator;

class SignatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var array */
    private static $config;

    /** @var Signator */
    private static $signator;

    public static function setUpBeforeClass()
    {
        $configFile = file_get_contents(__DIR__ . '/config.yml');
        $loader = new \Symfony\Component\Yaml\Yaml();
        self::$config = $loader->parse($configFile);
        self::$signator = new Signator(self::$config['env'], self::$config['cert'], self::$config['cert_password'], self::$config['api_key'], self::$config['api_endpoint']);
    }

    public function testSendSMS()
    {
        $sent = self::$signator->sendAuthentificationRequest(self::$config['sms_destination']);
        $this->assertTrue($sent);

        $validated = self::$signator->checkAuthentificationRequest(self::$config['sms_destination'], '000000');
        $this->assertFalse($validated);
    }

    public function testCreateSignOrder()
    {
        $path = __DIR__ . '/data/';

        $request = Request::create()
            ->addHolder('Firstname', 'Lastname', 'certisign@ylly.fr', '0601020304')
            ->addDocument('Test-1', $path . 'doc.pdf', false)
            ->addDocument('Test-2', $path . 'doc.pdf', false);

        $signInfo = new Signature($path . 'sign.png', 'Signature');

        $documents = self::$signator->signDocuments($request, $signInfo);

        $this->assertEquals(2, count($documents));
    }
}