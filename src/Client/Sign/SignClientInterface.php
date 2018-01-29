<?php

namespace YllyCertSign\Client\Sign;

use YllyCertSign\Client\LoggableClientInterface;

interface SignClientInterface extends LoggableClientInterface
{
    public function get($url);

    public function post($url, $content = []);
}
