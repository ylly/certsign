<?php

namespace YllyCertSign\Request;

use YllyCertSign\Request\Order\Holder;
use YllyCertSign\Request\Order\OTP;
use YllyCertSign\Request\Signature\Document;
use YllyCertSign\Request\Signature\Signature;

class Request
{
    /** @var Holder */
    public $holder;

    /** @var OTP */
    public $otp;

    /** @var Document[] */
    public $documents;

    /** @var string */
    private $clientId;

    /**
     * @param Holder|null $holder
     * @param Document[]|null $documents
     * @param OTP|null $otp
     */
    public function __construct($holder = null, $documents = null, $otp = null)
    {
        $this->holder = $holder;
        $this->documents = $documents;
        $this->otp = null !== $otp ? $otp : new OTP(false, '');
    }

    /**
     * @return Request
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @param string $name
     * @param string $data
     * @param bool $raw
     *
     * @return $this
     */
    public function addDocument($name, $data, Signature $signature = null, $raw = true)
    {
        $this->documents[] = new Document($name, $data, $signature, $raw);

        return $this;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $mobile
     *
     * @return $this
     */
    public function setHolder($firstname, $lastname, $email, $mobile)
    {
        $this->holder = new Holder($firstname, $lastname, $email, $mobile);

        return $this;
    }

    /**
     * @param $contact
     *
     * @return $this
     */
    public function setOTP($contact)
    {
        $this->otp = new OTP(true, $contact);

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }
}
