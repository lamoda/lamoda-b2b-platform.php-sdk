<?php

namespace LamodaB2B\Storage;

use LamodaB2B\Entity\AccessTokenRepository;
use LamodaB2B\Factory\AccessTokenFactoryInterface;
use LamodaB2B\HTTP\Model\AccessToken as AccessTokenModel;

class AccessTokenStorage implements AccessTokenStorageInterface
{
    /** @var AccessTokenRepository */
    protected $accessTokenRepository;

    /** @var AccessTokenFactoryInterface */
    protected $accessTokenFactory;

    /**
     * @param AccessTokenRepository       $accessTokenRepository
     * @param AccessTokenFactoryInterface $accessTokenFactory
     */
    public function __construct(AccessTokenRepository $accessTokenRepository, AccessTokenFactoryInterface $accessTokenFactory)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->accessTokenFactory    = $accessTokenFactory;
    }

    /**
     * @param string $partnerCode
     *
     * @return \LamodaB2B\HTTP\Model\AccessTokenInterface | null
     */
    public function getActiveToken($partnerCode)
    {
        return $this->accessTokenRepository->getActiveToken($partnerCode);
    }

    /**
     * @param string           $partnerCode
     * @param AccessTokenModel $accessTokenModel
     */
    public function saveToken($partnerCode, AccessTokenModel $accessTokenModel)
    {
        $accessToken = $this->accessTokenFactory->createAccessToken();
        $accessToken->setType($accessTokenModel->getType());
        $accessToken->setValue($accessTokenModel->getValue());
        $accessToken->setExpiresAt(time() + $accessTokenModel->getExpiresIn());
        $accessToken->setPartnerCode($partnerCode);

        $this->accessTokenRepository->persist($accessToken);
        $this->accessTokenRepository->flush($accessToken);
    }
}