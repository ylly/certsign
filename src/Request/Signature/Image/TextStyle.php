<?php

namespace YllyCertSign\Request\Signature\Image;

class TextStyle
{
    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var int */
    private $fontSize;

    /** @var int */
    private $spacing;

    /** @var Color */
    private $color;

    /**
     * @param int $x
     * @param int $y
     * @param int $fontSize
     * @param int $spacing
     */
    public function __construct($x, $y, $fontSize, $spacing, Color $color)
    {
        $this->x = $x;
        $this->y = $y;
        $this->fontSize = $fontSize;
        $this->spacing = $spacing;
        $this->color = $color;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param int $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @return int
     */
    public function getSpacing()
    {
        return $this->spacing;
    }

    /**
     * @param int $spacing
     */
    public function setSpacing($spacing)
    {
        $this->spacing = $spacing;
    }

    /**
     * @param $image
     *
     * @return int
     */
    public function getColor($image)
    {
        return imagecolorallocate($image, $this->color->red, $this->color->green, $this->color->blue);
    }

    /**
     * @param array $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }
}
