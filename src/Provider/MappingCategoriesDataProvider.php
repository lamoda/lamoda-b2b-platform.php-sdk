<?php

namespace LamodaB2B\Provider;

use Exception;
use LamodaB2B\HTTP\Client\LamodaB2BClient;
use LamodaB2B\HTTP\ConstantMessage;

class MappingCategoriesDataProvider
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
    public function getMappingCategories(string $partnerCode, array $params): array
    {
        $stockStateBody = $this->lamodaB2BClient->getMappingCategories($partnerCode, $params);
        if (!isset($stockStateBody['_embedded']['category_mapping'])
            || !is_array($stockStateBody['_embedded']['category_mapping'])
        ) {
            throw new Exception(ConstantMessage::UNEXPECTED_STRUCTURE_RESPONCE);
        }

        return $stockStateBody['_embedded']['category_mapping'];
    }
}