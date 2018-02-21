<?php

namespace YllyCertSign\Data;

class SignatureRequest
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array|object
     */
    private $data;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array|object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|object $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
