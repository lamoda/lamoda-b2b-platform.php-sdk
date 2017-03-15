<?php

namespace LamodaB2B\Storage;

use LamodaB2B\HTTP\Model\AccessToken;

interface AccessTokenStorageInterface
{
    /**
     * @param string $partnerCode
     *
     * @return \LamodaB2B\HTTP\Model\AccessTokenInterface | null
     */
    public function getActiveToken($partnerCode);

    /**
     * @param string $partnerCode
     * @param AccessToken $token
     */
    public function saveToken($partnerCode, AccessToken $token);
}