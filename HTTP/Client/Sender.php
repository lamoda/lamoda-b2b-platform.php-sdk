<?php

namespace LamodaB2B\HTTP\Client;

use GuzzleHttp\Exception\BadResponseException;
use LamodaB2B\HTTP\ConstantMessage;
use LamodaB2B\HTTP\Exception\HttpRequestException;
use LamodaB2B\HTTP\Response\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;
use Psr\Log\LogLevel;

class Sender
{
    const METHOD_POST = 'POST';
    const METHOD_GET  = 'GET';

    /** @var string */
    protected $baseUrl;

    /** @var LoggerInterface */
    protected $logger;

    /** @var bool */
    protected $isDebug;

    /**
     * @param string                 $baseUrl
     * @param LoggerInterface | null $logger
     * @param bool                   $isDebug
     */
    public function __construct($baseUrl, LoggerInterface $logger = null, $isDebug = false)
    {
        $this->baseUrl = $baseUrl;
        $this->logger  = $logger;
        $this->isDebug = $isDebug;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $headers
     * @param string $data
     * @param array  $query
     *
     * @return Response
     *
     * @throws HttpRequestException
     */
    public function sendRequest($uri, $method, $headers, $data = null, $query = [])
    {
        $fullUrl = $this->baseUrl . $uri;

        list($context, $privateContext) = $this->initLogContexts($data, $fullUrl, $method, $headers, $query);
        $client = $this->createClient();

        try {
            /** @var ResponseInterface $result */
            $result   = $client->request($method, $fullUrl, ['headers' => $headers, 'body' => $data, 'query' => $query]);

            $response = $this->createResponse($result->getStatusCode(), $result->getBody(), $result->getHeaders());

            $this->log(LogLevel::INFO,
                ConstantMessage::HTTP_REQUEST_SUCCESS,
                $this->prepareLogContext(array_merge([
                    'code'     => $response->getCode(),
                    'response' => $response->getBody(),
                ], $context), $privateContext)
            );

        } catch (BadResponseException $e) {
            $result   = $e->getResponse();
            $response = $this->createResponse($result->getStatusCode(), $result->getBody(), $result->getHeaders());

            $this->log(LogLevel::ERROR,
                ConstantMessage::HTTP_REQUEST_FAILED,
                $this->prepareLogContext(array_merge(
                    [
                        'code'     => $response->getCode(),
                        'response' => $response->getBody(),
                        'message'  => $e->getMessage(),
                    ], $context), $privateContext)
            );

        } catch (\Exception $e) {
            $this->log(LogLevel::ERROR, ConstantMessage::HTTP_REQUEST_ERROR, $this->prepareLogContext(array_merge([
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
            ], $context), $privateContext));

            throw new HttpRequestException(ConstantMessage::HTTP_REQUEST_ERROR, 0, $e);
        }

        return $response;
    }

    protected function log($level, $message, $context)
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->$level($message, $context);
        }
    }

    /**
     * @param array $context
     * @param array $privateContext
     *
     * @return array
     */
    protected function prepareLogContext(array $context, array $privateContext = [])
    {
        if ($this->isDebug == true) {
            $context = array_merge($context, $privateContext);
        }

        return $context;
    }

    /**
     * @return Client
     */
    protected function createClient()
    {
        return new Client();
    }

    /**
     * @param int    $code
     * @param string $body
     * @param array  $headers
     *
     * @return Response
     */
    protected function createResponse($code, $body, array $headers)
    {
        return new Response($code, $this->parseResponse($body), $headers);
    }

    /**
     * @param $body
     *
     * @return array
     */
    protected function parseResponse($body)
    {
        return json_decode($body, true);
    }

    /**
     * @param mixed  $data
     * @param string $uri
     * @param string $method
     * @param array  $headers
     * @param array  $query
     *
     * @return array
     */
    protected function initLogContexts($data, $uri, $method, array $headers, array $query)
    {
        $context = [
            'class'   => get_called_class(),
            'uri'     => $uri,
            'method'  => $method,
            'headers' => $headers,
        ];
        $privateContext = [
            'request_data' => $data,
            'query'        => $query,
        ];

        return [$context, $privateContext];
    }
}
