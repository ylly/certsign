<?php

use YllyCertiSign\Configurator;
use YllyCertiSign\Factory\SignatorFactory;
use YllyCertiSign\Signator;

class ConfiguratorTest extends \PHPUnit\Framework\TestCase
{
    public function testConfigureFromArray()
    {
        $config = Configurator::loadFromFile(__DIR__ . '/config.yml');
        $signator = SignatorFactory::createFromArray($config);

        $this->assertTrue($signator instanceof Signator);
    }

    public function testConfigureFromArrayWithProxy()
    {
        $config = Configurator::loadFromFile(__DIR__ . '/config.yml');
        $config['proxy'] = '127.0.0.1:8080';
        $signator = SignatorFactory::createFromArray($config);

        $this->assertTrue($signator instanceof Signator);
    }

    public function testConfigureFromFile()
    {
        $signator = SignatorFactory::createFromYamlFile(__DIR__ . '/config.yml');

        $this->assertTrue($signator instanceof Signator);
    }
}