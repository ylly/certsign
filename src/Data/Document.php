<?php

namespace YllyCertiSign\Data;

class Document
{
    public $name;
    public $data;

    public function __construct($name, $data, $raw = true)
    {
        $this->name = $name;
        $this->data = $data;

        if (!$raw) {
            $this->data = self::getDocumentData($this->data);
        }
    }

    public static function getDocumentData($path)
    {
        $content = file_get_contents($path);
        return base64_encode($content);
    }
}