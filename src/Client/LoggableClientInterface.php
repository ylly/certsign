<?php

namespace YllyCertiSign\Client;

use YllyCertiSign\Log\LogListenerInterface;

interface LoggableClientInterface
{
    public function writeLog($level, $message);

    public function addListener(LogListenerInterface $listener);

    public function emit($level, $message);
}
