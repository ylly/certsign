<?php

namespace YllyCertiSign\Data;

class Signature
{
    public $image;
    public $text;
    public $posX;
    public $posY;
    public $size;
    public $color;

    public function __construct($posX = 10, $posY = 10, $size = 10, $color = 8888)
    {
        $this->text = '';
        $this->posX = $posX;
        $this->posY = $posY;
        $this->size = $size;
        $this->color = $color;
    }

    public static function create()
    {
        return new Signature();
    }

    /**
     * @param Image|string $image
     * @param bool $raw
     * @return $this
     */
    public function setImage($image, $raw = true)
    {
        if ($image instanceof Image) {
            $this->image = $image->toBase64();
        } else {
            if ($raw) {
                $this->image = $image;
            } else {
                $this->image = Document::getDocumentData($image);
            }
        }
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}