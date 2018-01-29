<?php

namespace YllyCertSign\Client\SMS;

use YllyCertSign\Client\LoggableClientInterface;

interface SMSClientInterface extends LoggableClientInterface
{
    public function call($method, $args);
}
