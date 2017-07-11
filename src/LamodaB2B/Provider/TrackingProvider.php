<?php

namespace LamodaB2B\Provider;

use LamodaB2B\HTTP\Client\LamodaB2BClient;
use LeosPartnerDto\Dto\TrackingDto;

class TrackingProvider
{
    /** @var LamodaB2BClient */
    private $lamodaB2BClient;

    /**
     * @param LamodaB2BClient $lamodaB2BClient
     */
    public function __construct(LamodaB2BClient $lamodaB2BClient)
    {
        $this->lamodaB2BClient = $lamodaB2BClient;
    }

    /**
     * @return LamodaB2BClient
     */
    public function getLamodaB2BClient()
    {
        return $this->lamodaB2BClient;
    }

    /**
     * @param string $trackingId
     *
     * @return TrackingDto
     * @throws \LamodaB2B\HTTP\Exception\HttpRequestException
     */
    public function getTrackingDtoByTrackingId($trackingId)
    {
        $rawResponse = $this->getLamodaB2BClient()->getOrderTracking($trackingId);

        $cleanResponse = $this->getCleanResponse($rawResponse);

        $trackingDto = $this->buildTrackingDto($cleanResponse);

        return $trackingDto;
    }

    /**
     * @param string $packBarcode
     *
     * @return TrackingDto
     * @throws \LamodaB2B\HTTP\Exception\HttpRequestException
     */
    public function getTrackingDtoByPackBarcode($packBarcode)
    {
        $rawResponse = $this->getLamodaB2BClient()->getPackTracking($packBarcode);
        if ($rawResponse === null){
            return $rawResponse;
        }

        $cleanResponse = $this->getCleanResponse($rawResponse);

        $trackingDto = $this->buildTrackingDto($cleanResponse);

        return $trackingDto;
    }

    /**
     * @param array $response
     *
     * @return TrackingDto
     */
    protected function buildTrackingDto(array $response)
    {
        $trackingDto = $this->trackingDtoFactoryMethod();
        $trackingDto->setOrderNr($response['orderNr']);
        $trackingDto->setTrackingId($response['trackingId']);
        $trackingDto->setDeliveryDate($response['deliveryDate']);
        $trackingDto->setDeliveryIntervalFrom($response['deliveryIntervalFrom']);
        $trackingDto->setDeliveryIntervalTo($response['deliveryIntervalTo']);
        $trackingDto->setOrderRepresentStatus($response['orderRepresentStatus']);

        return $trackingDto;
    }

    /**
     * @return TrackingDto
     */
    protected function trackingDtoFactoryMethod()
    {
        $trackingDto = new TrackingDto();

        return $trackingDto;
    }


    /**
     * @param array $rawResponse
     *
     * @return array
     */
    protected function getCleanResponse(array $rawResponse)
    {
        $default  = [
            'orderNr'              => null,
            'trackingId'           => null,
            'deliveryDate'         => null,
            'deliveryIntervalFrom' => null,
            'deliveryIntervalTo'   => null,
            'orderRepresentStatus' => null

        ];
        $response = array_merge($default, $rawResponse);

        return $response;
    }
}