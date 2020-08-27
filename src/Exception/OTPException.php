<?php

namespace YllyCertSign\Exception;

use Exception;

class OTPException extends WebserviceException
{
    /**
     * @var int
     */
    private $remainingTries = 0;

    /**
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        preg_match('/encore (\d+) essai/', $message, $matches);

        if (isset($matches[1])) {
            $this->remainingTries = $matches[1];
        }
    }

    /**
     * @return int
     */
    public function getRemainingTries()
    {
        return $this->remainingTries;
    }
}
