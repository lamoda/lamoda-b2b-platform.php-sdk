<?php

namespace LamodaB2B\HTTP\Client;

use LamodaB2B\HTTP\Exception\ClientErrorException;
use LamodaB2B\HTTP\Exception\NotFoundException;
use LamodaB2B\HTTP\Model\AccessTokenInterface;
use LamodaB2B\HTTP\Response\Response as HttpResponse;
use LamodaB2B\Storage\AccessTokenStorageInterface;
use LeosPartnerDto\Dto\Fulfilment\FulfilmentShipment;
use LeosPartnerDto\Dto\Fulfilment\Nomenclature;
use LeosPartnerDto\Dto\Order\OrderDtoOrder;
use LeosPartnerDto\Dto\Shipment\Out\ShipmentOutDtoShipment;
use LamodaB2B\HTTP\Exception\HttpRequestException;
use LamodaB2B\HTTP\ConstantMessage;

class LamodaB2BClient
{
    const URI_API_V1_PACKS_TRACKING       = '/api/v1/packsTracking/%s';
    const URI_API_V1_ORDERS_TRACKING      = '/api/v1/ordersTracking/%s';
    const URI_API_V1_SHIPMENTS_OUT        = '/api/v1/shipments/out';
    const URI_API_V1_ORDERS               = '/api/v1/orders';
    const URI_API_V1_ORDER                = '/api/v1/orders/%s';
    const URI_API_V1_NOMENCLATURES        = '/api/v1/nomenclatures';
    const URI_API_V1_FULFILMENT_SHIPMENTS = '/api/v1/shipments/fulfilment';
    const URI_API_V1_GET_STOCK_STATE      = '/api/v1/stock/goods';

    /** @var Sender */
    protected $sender;

    /** @var AuthLamodaB2BClient */
    protected $authLamodaB2BClient;

    /** @var AccessTokenStorageInterface */
    protected $accessTokenStorage;

    /**
     * @param Sender                     $sender
     * @param AuthLamodaB2BClient        $authLamodaB2BClient
     * @param AccessTokenStorageInterface $accessTokenStorage
     */
    public function __construct(
        Sender                      $sender,
        AuthLamodaB2BClient         $authLamodaB2BClient,
        AccessTokenStorageInterface $accessTokenStorage
    ) {
        $this->sender              = $sender;
        $this->authLamodaB2BClient = $authLamodaB2BClient;
        $this->accessTokenStorage  = $accessTokenStorage;
    }

    /**
     * @param OrderDtoOrder $order
     * @param string        $partnerCode
     *
     * @return \LamodaB2B\HTTP\Response\Response
     */
    public function sendOrder(OrderDtoOrder $order, $partnerCode)
    {
        return $this->sendRequest($partnerCode, self::URI_API_V1_ORDERS, Sender::METHOD_POST, $order);
    }

    /**
     * @param ShipmentOutDtoShipment $shipment
     * @param string                 $partnerCode
     *
     * @return \LamodaB2B\HTTP\Response\Response
     */
    public function sendShipmentOut(ShipmentOutDtoShipment $shipment, $partnerCode)
    {
        return $this->sendRequest($partnerCode, self::URI_API_V1_SHIPMENTS_OUT, Sender::METHOD_POST, $shipment);
    }

    /**
     * @param Nomenclature $nomenclature
     * @param string $partnerCode
     *
     * @return \LamodaB2B\HTTP\Response\Response
     */
    public function sendNomenclature(Nomenclature $nomenclature, $partnerCode)
    {
        return $this->sendRequest($partnerCode, self::URI_API_V1_NOMENCLATURES, Sender::METHOD_POST, $nomenclature);
    }

    /**
     * @param FulfilmentShipment $fulfilmentShipment
     * @param string $partnerCode
     *
     * @return \LamodaB2B\HTTP\Response\Response
     */
    public function sendFulfilmentShipment(FulfilmentShipment $fulfilmentShipment, $partnerCode)
    {
        return $this->sendRequest($partnerCode, self::URI_API_V1_FULFILMENT_SHIPMENTS, Sender::METHOD_POST, $fulfilmentShipment);
    }

    /**
     * @param string $partnerCode
     * @param array  $params
     *
     * @return string
     */
    public function getStockState($partnerCode, array $params)
    {
        $accessToken = $this->getAccessToken($partnerCode);

        $stockStateResponse = $this->sender->sendRequest(
            self::URI_API_V1_GET_STOCK_STATE,
            Sender::METHOD_GET,
            $this->getHeaders([
                'Authorization' => $this->getAuthString($accessToken),
            ]),
            null,
            $params
        );

        $this->parseResponse($stockStateResponse);

        return $stockStateResponse->getBody();
    }

    /**
     * @param $partnerCode
     * @param $trackingId
     *
     * @return string
     * @throws HttpRequestException
     */
    public function getOrderInfo($partnerCode, $trackingId)
    {
        $accessToken = $this->getAccessToken($partnerCode);
        $uri = sprintf(self::URI_API_V1_ORDER, $trackingId);

        $orderResponse = $this->sender->sendRequest(
            $uri,
            Sender::METHOD_GET,
            $this->getHeaders([
                'Authorization' => $this->getAuthString($accessToken),
            ])
        );

        $this->parseResponse($orderResponse);

        return $orderResponse->getBody();
    }

    /**
     * @param $trackingId
     *
     * @return string
     * @throws ClientErrorException
     * @throws HttpRequestException
     */
    public function getOrderTracking($trackingId)
    {
        $uri = sprintf(self::URI_API_V1_ORDERS_TRACKING, $trackingId);

        $orderTrackingResponse = $this->sender->sendRequest(
            $uri,
            Sender::METHOD_GET,
            $this->getHeaders()
        );

        $this->parseTrackingResponse($orderTrackingResponse);

        return $orderTrackingResponse->getBody();
    }

    /**
     * @param $trackingId
     *
     * @return \LamodaB2B\HTTP\Response\Response
     * @throws HttpRequestException
     */
    public function getPackTracking($trackingId)
    {
        $uri = sprintf(self::URI_API_V1_PACKS_TRACKING, $trackingId);

        $packTrackingResponse = $this->sender->sendRequest(
            $uri,
            Sender::METHOD_GET,
            $this->getHeaders()
        );

        $this->parseTrackingResponse($packTrackingResponse);

        return $packTrackingResponse->getBody();
    }

    /**
     * @param string $partnerCode
     * @param string $path
     * @param string $method
     * @param mixed  $data
     *
     * @return \LamodaB2B\HTTP\Response\Response
     *
     * @throws HttpRequestException
     */
    protected function sendRequest($partnerCode, $path, $method, $data)
    {
        $accessToken = $this->getAccessToken($partnerCode);

        return $this->sender->sendRequest(
            $path,
            $method,
            $this->getHeaders(['Authorization' => $this->getAuthString($accessToken)]),
            json_encode($data, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * @param string $partnerCode
     *
     * @return AccessTokenInterface | null
     */
    protected function getAccessToken($partnerCode)
    {
        $token = $this->accessTokenStorage->getActiveToken($partnerCode);

        if (!($token instanceof AccessTokenInterface)) {
            $token = $this->authLamodaB2BClient->getAccessToken($partnerCode);
            $this->accessTokenStorage->saveToken($partnerCode, $token);
        }

        return $token;
    }

    /**
     * @param AccessTokenInterface $accessToken
     *
     * @return string
     */
    protected function getAuthString(AccessTokenInterface $accessToken)
    {
        return ucfirst($accessToken->getType()) . ' ' . $accessToken->getValue();
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

    /**
     * @param HttpResponse $trackingResponse
     *
     * @throws ClientErrorException
     * @throws HttpRequestException
     * @throws NotFoundException
     */
    protected function parseTrackingResponse(HttpResponse $trackingResponse)
    {
        switch($trackingResponse){
            case ($trackingResponse->isSuccess()):
                break;
            case ($trackingResponse->getCode() === HttpResponse::HTTP_NOT_FOUND):
                throw new NotFoundException(ConstantMessage::TRACKING_IS_NOT_FOUND);
                break;
            case ($trackingResponse->isClientError()):
                throw new ClientErrorException(ConstantMessage::CLIENT_ERROR_TRACKING);
                break;
            default:
                throw new HttpRequestException(ConstantMessage::FAILED_TO_GET_TRACKING);
        }
    }

    protected function parseResponse(HttpResponse $response)
    {
        switch ($response) {
            case ($response->isSuccess()):
                break;
            case ($response->getCode() === HttpResponse::HTTP_NOT_FOUND):
                throw new HttpRequestException(ConstantMessage::HTTP_REQUEST_FAILED, $response->getCode());
        }
    }
}