<?php
declare(strict_types=1);

namespace LamodaB2B\Model;

class Auth
{
    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}