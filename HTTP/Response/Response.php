<?php

namespace LamodaB2B\HTTP\Response;

class Response
{
    const HTTP_OK                    = 200;
    const HTTP_MULTIPLE_CHOICES      = 300;
    const HTTP_BAD_REQUEST           = 400;
    const HTTP_NOT_FOUND             = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @var int */
    protected $code;

    /** @var string */
    protected $body;

    /** @var array */
    protected $headers;

    /**
     * @param int    $code
     * @param string $body
     * @param array  $headers
     */
    public function __construct($code, $body, array $headers = [])
    {
        $this->code    = $code;
        $this->body    = $body;
        $this->headers = $headers;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->code >= self::HTTP_OK && $this->code < self::HTTP_MULTIPLE_CHOICES;
    }

    /**
     * @return bool
     */
    public function isClientError()
    {
        return $this->code >= self::HTTP_BAD_REQUEST && $this->code < self::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @return bool
     */
    public function isServerError()
    {
        return $this->code >= self::HTTP_INTERNAL_SERVER_ERROR;
    }
}