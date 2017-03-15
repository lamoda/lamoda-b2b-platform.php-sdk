<?php

namespace LamodaB2B\HTTP\Model;

interface AccessTokenInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();
}