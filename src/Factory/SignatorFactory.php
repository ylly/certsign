<?php

namespace YllyCertSign\Factory;

use YllyCertSign\Client\Sign\SignClient;
use YllyCertSign\Configurator;
use YllyCertSign\Signator;

class SignatorFactory
{
    /**
     * @param string $environnement
     * @param string $certAbsolutePath
     * @param string $certPassword
     * @param string|null $proxy
     * @return Signator
     */
    public static function create($environnement, $certAbsolutePath, $certPassword, $proxy)
    {
        $client = new SignClient($environnement, $certAbsolutePath, $certPassword, $proxy);
        return new Signator($client);
    }

    /**
     * @param array $config
     * @return Signator
     */
    public static function createFromArray(array $config)
    {
        return self::create(
            $config['env'],
            $config['cert'],
            $config['cert_password'],
            isset($config['proxy']) ? $config['proxy'] : null
        );
    }

    /**
     * @param string $absolutePath
     * @return Signator
     */
    public static function createFromYamlFile($absolutePath)
    {
        $config = Configurator::loadFromFile($absolutePath);
        return self::createFromArray($config);
    }
}
