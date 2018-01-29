<?php

namespace YllyCertiSign\Data;

class Color
{
    /** @var int */
    public $red;

    /** @var int */
    public $green;

    /** @var int */
    public $blue;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function __construct($red, $green, $blue)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }
}