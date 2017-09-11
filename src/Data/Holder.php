<?php

namespace YllyCertiSign\Data;

class Holder
{
    public $firstname;
    public $lastname;
    public $email;
    public $mobile;

    public function __construct($firstname, $lastname, $email, $mobile)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->mobile = $mobile;
    }
}