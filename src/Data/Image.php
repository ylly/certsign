<?php

namespace YllyCertiSign\Data;

class Image
{
    private $image;

    public function __construct($w, $h, $r, $g, $b)
    {
        $this->image = imagecreate($w, $h);
        imagecolorallocate($this->image, $r, $g, $b);
    }

    public function addText($text, $x, $y, $size, $r, $g, $b)
    {
        $color = imagecolorallocate($this->image, $r, $g, $b);
        $font = __DIR__ . '/../arial.ttf';
        imagettftext($this->image, $size, 0, $x, $y, $color, $font, $text);
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