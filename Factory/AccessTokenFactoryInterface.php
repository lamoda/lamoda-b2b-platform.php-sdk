<?php

namespace LamodaB2B\Factory;

interface AccessTokenFactoryInterface
{
    /**
     * @return \LamodaB2B\Entity\AccessToken
     */
    public function createAccessToken();
}