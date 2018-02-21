<?php

namespace YllyCertSign\Data;

class Request
{
    /** @var Holder */
    public $holder;

    /** @var OTP */
    public $otp;

    /** @var Document[] */
    public $documents;

    /**
     * @param Holder|null $holder
     * @param Document[]|null $documents
     * @param OTP|null $otp
     */
    public function __construct($holder = null, $documents = null, $otp = null)
    {
        $this->holder = $holder;
        $this->documents = $documents;
        $this->otp = $otp !== null ? $otp : new OTP(false, '');
    }

    /**
     * @return Request
     */
    public static function create()
    {
        return new Request();
    }

    /**
     * @param string $name
     * @param string $data
     * @param Signature|null $signature
     * @param bool $raw
     * @return $this
     */
    public function addDocument($name, $data, Signature $signature = null, $raw = true)
    {
        $document = new Document($name, $data, $signature, $raw);
        $this->documents[] = $document;
        return $this;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $mobile
     * @return $this
     */
    public function setHolder($firstname, $lastname, $email, $mobile)
    {
        $this->holder = new Holder($firstname, $lastname, $email, $mobile);
        return $this;
    }

    /**
     * @param $contact
     * @return $this
     */
    public function setOTP($contact)
    {
        $this->otp = new OTP(true, $contact);
        return $this;
    }
}
