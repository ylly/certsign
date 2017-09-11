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
        self::$signator = Signator::createFromYaml(__DIR__ . '/config.yml');
    }

    public function testSendSMS()
    {
        $config = Configurator::loadFromFile(__DIR__ . '/config.yml');

        $sent = self::$signator->sendAuthentificationRequest($config['sms_destination']);
        $this->assertTrue($sent);

        $validated = self::$signator->checkAuthentificationRequest($config['sms_destination'], '000000');
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