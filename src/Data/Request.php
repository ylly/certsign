<?php

namespace YllyCertSign\Data;

class Request
{
    /** @var Holder */
    public $holder;

    /** @var Document[] */
    public $documents;

    /**
     * @param Holder|null $holder
     * @param Document[]|null $documents
     */
    public function __construct($holder = null, $documents = null)
    {
        $this->holder = $holder;
        $this->documents = $documents;
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
}
