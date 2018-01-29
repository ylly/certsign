<?php

namespace YllyCertiSign\Client;

use YllyCertiSign\Log\LogListenerInterface;

abstract class AbstractClient implements LoggableClientInterface
{
    const INFO = 'info';
    const ERROR = 'error';

    /**
     * @var LogListenerInterface[]
     */
    private $listeners = [];

    public function addListener(LogListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    public function emit($level, $message)
    {
        foreach ($this->listeners as $listener) {
            $listener->recieve($level, $message);
        }
    }

    public function writeLog($level, $message)
    {
        $this->emit($level, $message);
    }
}
