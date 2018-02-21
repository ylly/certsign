<?php

namespace YllyCertSign\Factory;

use YllyCertSign\Client\Sign\SignClient;
use YllyCertSign\Configurator;
use YllyCertSign\Signator;

class SignatorFactory
{
    /**
     * @param string $environnement
     * @param string $certPath
     * @param string $certPassword
     * @param string|null $proxy
     * @return Signator
     */
    public static function create($environnement, $certPath, $certPassword, $proxy)
    {
        $client = new SignClient($environnement, $certPath, $certPassword, $proxy);
        return new Signator($client);
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
