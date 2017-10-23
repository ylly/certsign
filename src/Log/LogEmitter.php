<?php

namespace YllyCertiSign\Log;

class LogEmitter
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

    protected function emit($level, $message)
    {
        foreach ($this->listeners as $listener) {
            $listener->recieve($level, $message);
        }
    }
}