<?php
declare(strict_types=1);

namespace LamodaB2B\Storage;

use LamodaB2B\Exception\AuthConfigurationException;
use LamodaB2B\Model\Auth;

class ArrayAuthStorage implements AuthStorageInterface
{
    const KEY_CLIENT_ID     = 'client_id';
    const KEY_CLIENT_SECRET = 'client_secret';

    protected $authList;

    public function __construct(array $authList)
    {
        $this->authList = $authList;
    }

    public function get(string $identity = null): Auth
    {
        if (
            array_key_exists($identity, $this->authList)
            && array_key_exists(static::KEY_CLIENT_ID, $this->authList[$identity])
            && array_key_exists(static::KEY_CLIENT_SECRET, $this->authList[$identity])
        ) {
            return new Auth(
                $this->authList[$identity][static::KEY_CLIENT_ID],
                $this->authList[$identity][static::KEY_CLIENT_SECRET]
            );
        } else {
            throw new AuthConfigurationException('Unable to get authentication parameters from config');
        }
    }
}