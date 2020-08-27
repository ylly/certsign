<?php

use PHPUnit\Framework\TestCase;
use YllyCertSign\Request\Signature\Image\Color;
use YllyCertSign\Request\Signature\Image\Image;
use YllyCertSign\Request\Signature\Image\TextStyle;
use YllyCertSign\Request\Signature\Signature;

class SignatureTest extends TestCase
{
    public function testSignature()
    {
        $image = new Image(50, 50, new Color(255, 255, 255));
        $image->setStyle(new TextStyle(0, 0, 14, 8, new Color(0, 0, 0)));
        $image->addText('Text');

        $signature = Signature::create()->setImage($image);
        $this->assertNotNull($signature->image);

        $imagePath = __DIR__ . '/data/sign.png';

        $signature = Signature::create()->setImage($imagePath, false);
        $this->assertNotNull($signature->image);

        $signature = Signature::create()->setImage(file_get_contents($imagePath));
        $this->assertNotNull($signature->image);
    }
}
