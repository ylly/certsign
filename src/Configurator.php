<?php

namespace YllyCertiSign;

use Symfony\Component\Yaml\Yaml;

class Configurator
{
    public static function loadFromFile($pathToFile)
    {
        $configFile = file_get_contents($pathToFile);
        return Yaml::parse($configFile);
    }
}