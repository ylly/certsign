<?php

namespace YllyCertSign;

use Symfony\Component\Yaml\Yaml;

class Configurator
{
    /**
     * @param string $pathToFile
     * @return array
     */
    public static function loadFromFile($pathToFile)
    {
        $configFile = file_get_contents($pathToFile);
        return Yaml::parse($configFile);
    }
}
