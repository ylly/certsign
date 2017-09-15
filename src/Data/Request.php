<?php

namespace YllyCertiSign\Data;

class Request
{
    /** @var Holder */
    public $holder;
    /** @var Document[] */
    public $documents;

    public function __construct($holder = null, $documents = null)
    {
        $this->holder = $holder;
        $this->documents = $documents;
    }

    public static function create()
    {
        return new Request();
    }

    public function addDocument($name, $data, Signature $signature = null, $raw = true)
    {
        $document = new Document($name, $data, $signature, $raw);
        $this->documents[] = $document;
        return $this;
    }

    public function addHolder($firstname, $lastname, $email, $mobile)
    {
        $this->holder = new Holder($firstname, $lastname, $email, $mobile);
        return $this;
    }
}