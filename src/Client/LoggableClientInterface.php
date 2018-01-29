<?php

namespace YllyCertSign\Client;

use YllyCertSign\Log\LogListenerInterface;

interface LoggableClientInterface
{
    public function writeLog($level, $message);

    public function addListener(LogListenerInterface $listener);

    public function emit($level, $message);
}
