<?php

namespace LamodaB2B\HTTP\Client;

use LamodaB2B\HTTP\ConstantMessage;
use LamodaB2B\HTTP\Exception\HttpRequestException;
use LamodaB2B\HTTP\Model\AccessToken;
use LamodaB2B\Storage\AuthStorageInterface;

class AuthLamodaB2BClient
{
    const URI_AUTH_TOKEN = '/auth/token';

    /** @var Sender */
    protected $sender;

    /** @var string */
    protected $grantType;

    /** @var  AuthStorageInterface */
    protected $authStorage;

    /**
     * @param Sender $sender
     * @param string $grantType
     * @param AuthStorageInterface $authStorage
     */
    public function __construct(
        Sender               $sender,
        string               $grantType,
        AuthStorageInterface $authStorage

    ) {
        $this->sender      = $sender;
        $this->grantType   = $grantType;
        $this->authStorage = $authStorage;
    }

    /**
     * @param string $identity
     *
     * @return AccessToken
     *
     * @throws HttpRequestException
     */
    public function getAccessToken(string $identity = null): AccessToken
    {
        $tokenResponse = $this->getNewAccessToken($identity);

        if (!$tokenResponse->isSuccess()) {
            throw new HttpRequestException(ConstantMessage::FAILED_TO_GET_TOKEN);
        }

        $responseBody = $tokenResponse->getBody();
        $accessToken  = $this->createAccessToken(
            $responseBody['token_type'],
            $responseBody['access_token'],
            $responseBody['expires_in']
        );

        return $accessToken;
    }

    /**
     * @param null|string $identity
     * @return \LamodaB2B\HTTP\Response\Response
     */
    protected function getNewAccessToken(string $identity = null)
    {
        $auth = $this->authStorage->get($identity);

        return $this->sender->sendRequest(
            self::URI_AUTH_TOKEN, Sender::METHOD_GET, $this->getHeaders(), null, [
            'client_id'     => $auth->getClientId(),
            'client_secret' => $auth->getClientSecret(),
            'grant_type'    => $this->grantType,
        ]);
    }

    /**
     * @param string $tokenType
     * @param string $value
     * @param int    $expiresIn
     *
     * @return AccessToken
     */
    protected function createAccessToken($tokenType, $value, $expiresIn)
    {
        return new AccessToken($tokenType, $value, $expiresIn);
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    protected function getHeaders(array $headers = [])
    {
        return array_merge([
            'Content-Type' => 'application/json',
            'Accept'       => '*/*'
        ], $headers);
    }
}