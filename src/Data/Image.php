<?php

namespace YllyCertiSign\Data;

class Image
{
    private $image;

    /** @var TextStyle */
    private $style;

    /** @var int */
    private $textIndex = 0;

    public function __construct($w, $h, $r, $g, $b)
    {
        $this->setStyle(new TextStyle(0, 10, 7, 15, [0, 0, 0]));
        $this->image = imagecreate($w, $h);
        imagecolorallocate($this->image, $r, $g, $b);
    }

    public function setStyle(TextStyle $style)
    {
        $this->style = $style;
        $this->textIndex = 0;
    }

    public function addText($text)
    {
        $font = __DIR__ . '/../arial.ttf';

        $x = $this->style->getX();
        $y = $this->style->getY() + $this->style->getSpacing() * $this->textIndex;
        ++$this->textIndex;

        imagettftext($this->image, $this->style->getFontSize(), 0, $x, $y, $this->style->getColor($this->image), $font, $text);
    }

    public function toBase64()
    {
        ob_start();
        imagepng($this->image);
        $data = ob_get_contents();
        ob_end_clean();
        imagedestroy($this->image);

        return base64_encode($data);
    }
}