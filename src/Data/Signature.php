<?php

namespace YllyCertSign\Data;

class Signature
{
    /** @var string */
    public $image;

    /** @var int */
    public $posX;

    /** @var int */
    public $posY;

    /** @var int */
    public $page;

    /**
     * @param int $posX
     * @param int $posY
     * @param int $page
     */
    public function __construct($posX = 10, $posY = 10, $page = 1)
    {
        $this->posX = $posX;
        $this->posY = $posY;
        $this->page = $page;
    }

    /**
     * @return Signature
     */
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
}
