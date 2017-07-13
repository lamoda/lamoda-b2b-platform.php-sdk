<?php

namespace LamodaB2B\Factory;

use LamodaB2B\Entity\AccessToken;

class AccessTokenFactory implements AccessTokenFactoryInterface
{
    /**
     * @return \LamodaB2B\Entity\AccessToken
     */
    public function createAccessToken()
    {
        return new AccessToken();
    }
}
