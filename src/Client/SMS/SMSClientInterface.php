<?php

namespace YllyCertiSign\Client\SMS;

use YllyCertiSign\Client\LoggableClientInterface;

interface SMSClientInterface extends LoggableClientInterface
{
    public function call($method, $args);
}