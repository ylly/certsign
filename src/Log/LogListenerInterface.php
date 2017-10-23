<?php

namespace YllyCertiSign\Log;

interface LogListenerInterface
{
    public function recieve($level, $message);
}