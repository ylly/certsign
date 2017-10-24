<?php

namespace YllyCertiSign\Data;

class TextStyle
{
    private $x;

    private $y;

    private $fontSize;

    private $spacing;

    private $color;

    /**
     * @param int $x
     * @param int $y
     * @param int $fontSize
     * @param int $spacing
     * @param array $color
     */
    public function __construct($x, $y, $fontSize, $spacing, array $color)
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
     * @return int
     */
    public function getColor($image)
    {
        return imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);
    }

    /**
     * @param array $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }
}