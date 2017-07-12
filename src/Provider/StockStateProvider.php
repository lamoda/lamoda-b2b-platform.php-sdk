<?php

namespace LamodaB2B\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use LamodaB2B\HTTP\Client\LamodaB2BClient;
use LamodaB2B\HTTP\ConstantMessage;
use LeosPartnerDto\Dto\StockStateDto;
use LeosPartnerDto\Dto\StockStateItemDto;

class StockStateProvider
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
     * @param string $partnerCode
     * @param array  $params
     *
     * @throws Exception
     *
     * @return array
     */
    public function getStockState($partnerCode, array $params)
    {
        $stockStateBody = $this->lamodaB2BClient->getStockState($partnerCode, $params);
        if (!isset($stockStateBody['_embedded']['stockStates'])
            || !is_array($stockStateBody['_embedded']['stockStates'])
        ) {
            throw new Exception(ConstantMessage::UNEXPECTED_STRUCTURE_RESPONCE);
        }

        return $stockStateBody['_embedded']['stockStates'];
    }

    /**
     * @param array $items
     *
     * @return StockStateDto
     */
    public function buildStockDto(array $items)
    {
        $stockState      = new StockStateDto();
        $stockStateItems = new ArrayCollection();

        foreach ($items as $item) {
            $itemDto = new StockStateItemDto();
            $itemDto->setSku($item['sku']);
            $itemDto->setOnHandQuantity($item['quantity']);

            $stockStateItems->add($itemDto);
        }

        $stockState->setItems($stockStateItems);

        return $stockState;
    }
}