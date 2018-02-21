<?php

namespace YllyCertSign\Data;

class OTP
{
    /**
     * @var bool
     */
    public $enabled = false;

    /**
     * @var string
     */
    public $contact;

    /**
     * @param bool $enabled
     * @param string $contact
     */
    public function __construct($enabled, $contact)
    {
        $this->enabled = $enabled;
        $this->contact = $contact;
    }
}