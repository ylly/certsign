<?php

namespace YllyCertiSign\Client\Sign;

use YllyCertiSign\Client\LoggableClientInterface;

interface SignClientInterface extends LoggableClientInterface
{
    public function get($url);

    public function post($url, $content = []);
}