<?php

namespace YllyCertiSign\Factory;

use YllyCertiSign\Client\Sign\SignClient;
use YllyCertiSign\Client\SMS\SMSClient;
use YllyCertiSign\Configurator;
use YllyCertiSign\Signator;

class SignatorFactory
{
    /**
     * @param string $environnement
     * @param string $certPath
     * @param string $certPassword
     * @param string $apiKey
     * @param string $domain
     * @param string|null $proxy
     * @return Signator
     */
    public static function create($environnement, $certPath, $certPassword, $apiKey, $domain, $proxy)
    {
        $signClient = new SignClient($environnement, $certPath, $certPassword, $proxy);
        $smsClient = new SMSClient($environnement, $apiKey, $proxy);
        return new Signator($signClient, $smsClient, $domain);
    }

    /**
     * @param array $config
     * @return Signator
     */
    public static function createFromArray($config)
    {
        return static::create(
            $config['env'],
            $config['cert'],
            $config['cert_password'],
            $config['api_key'],
            $config['api_endpoint'],
            isset($config['proxy']) ? $config['proxy'] : null
        );
    }

    /**
     * @param string $pathToFile
     * @return Signator
     */
    public static function createFromYamlFile($pathToFile)
    {
        $config = Configurator::loadFromFile($pathToFile);
        return self::createFromArray($config);
    }
}