<?php

namespace LamodaB2B\HTTP\Model;

class AccessToken implements AccessTokenInterface
{
    /** @var string */
    public $type;

     /** @var string */
    public $value;

    /** @var int */
    public $expiresIn;

    /**
     * @param string $type
     * @param string $value
     * @param int    $expiresIn
     */
    public function __construct($type, $value, $expiresIn)
    {
        $this->type      = $type;
        $this->value     = $value;
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }
}
