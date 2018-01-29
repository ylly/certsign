<?php

namespace YllyCertiSign\Data;

class Document
{
    /** @var string */
    public $name;

    /** @var string */
    public $data;

    /** @var Signature|null */
    public $signature;

    /**
     * @param string $name
     * @param string $data
     * @param Signature|null $signature
     * @param bool $raw
     */
    public function __construct($name, $data, Signature $signature = null, $raw = true)
    {
        $this->name = $name;
        $this->data = $data;
        $this->signature = $signature;

        if (!$raw) {
            $this->data = self::getDocumentData($this->data);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getDocumentData($path)
    {
        $content = file_get_contents($path);
        return base64_encode($content);
    }
}