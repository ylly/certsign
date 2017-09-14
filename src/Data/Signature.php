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

    public function __construct($imagePath, $text, $posX = 10, $posY = 10, $size = 10, $color = 8888)
    {
        if (file_exists($imagePath)) {
            $this->image = Document::getDocumentData($imagePath);
        } else {
            $this->image = '';
        }

        $this->text = $text;
        $this->posX = $posX;
        $this->posY = $posY;
        $this->size = $size;
        $this->color = $color;
    }
}