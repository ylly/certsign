<?php

namespace YllyCertiSign\Client;

use YllyCertiSign\Log\LogEmitter;

abstract class AbstractClient extends LogEmitter
{
    public function writeLog($level, $message)
    {
        $this->emit($level, $message);
    }
}