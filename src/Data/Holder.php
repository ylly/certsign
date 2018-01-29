<?php

namespace YllyCertSign\Data;

class Holder
{
    /** @var string */
    public $firstname;

    /** @var string */
    public $lastname;

    /** @var string */
    public $email;

    /** @var string */
    public $mobile;

    /** @var string */
    public $country;

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $mobile
     * @param string|null $country
     */
    public function __construct($firstname, $lastname, $email, $mobile, $country = null)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->country = $country ?: 'FR';
    }
}
