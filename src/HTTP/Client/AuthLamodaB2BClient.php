<?php

namespace LamodaB2B\HTTP\Client;

use LamodaB2B\HTTP\Exception\InvalidArgumentException;
use LamodaB2B\HTTP\Model\AccessToken;
use LamodaB2B\HTTP\ConstantMessage;
use LamodaB2B\HTTP\Exception\HttpRequestException;

class AuthLamodaB2BClient
{
    const URI_AUTH_TOKEN = '/auth/token';

    /** @var Sender */
    protected $sender;

    /** @var string */
    protected $grantType;

    /** @var array */
    protected $authConfig;

    /**
     * @param Sender $sender
     * @param string $grantType
     * @param array  $authConfig
     */
    public function __construct(
        Sender $sender,
               $grantType,
        array  $authConfig

    ) {
        $this->sender       = $sender;
        $this->grantType    = $grantType;
        $this->authConfig   = $authConfig;
    }

    /**
     * @param string $partnerCode
     *
     * @return AccessToken
     *
     * @throws HttpRequestException
     */
    public function getAccessToken($partnerCode)
    {
        $tokenResponse = $this->getNewAccessToken($partnerCode);

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
     * @param string $partnerCode
     *
     * @return \LamodaB2B\HTTP\Response\Response
     *
     * @throws HttpRequestException
     */
    protected function getNewAccessToken($partnerCode)
    {
        return $this->sender->sendRequest(
            self::URI_AUTH_TOKEN, Sender::METHOD_GET, $this->getHeaders(), null, [
            'client_id'     => $this->getAuthParameter($partnerCode, 'client_id'),
            'client_secret' => $this->getAuthParameter($partnerCode, 'client_secret'),
            'grant_type'    => $this->grantType,
        ]);
    }

    /**
     * @param string $partnerCode
     * @param string $parameterName
     *
     * @return string
     */
    protected function getAuthParameter($partnerCode, $parameterName)
    {
        if (empty($this->authConfig[$partnerCode][$parameterName])) {
            throw new InvalidArgumentException(sprintf(ConstantMessage::MISSING_AUTH_PARAMETER, $parameterName, $partnerCode));
        }

        return $this->authConfig[$partnerCode][$parameterName];
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