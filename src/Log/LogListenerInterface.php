<?php

namespace YllyCertSign\Log;

interface LogListenerInterface
{
    public function recieve($level, $message);
}
