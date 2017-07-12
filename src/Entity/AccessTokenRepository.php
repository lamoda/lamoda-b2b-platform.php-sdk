<?php

namespace LamodaB2B\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class AccessTokenRepository extends EntityRepository
{
    /**
     * @param string $partnerCode
     *
     * @return AccessToken | null
     */
    public function getActiveToken($partnerCode)
    {
        /** @var Criteria $criteria */
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->gt('expiresAt', time()));
        $criteria->andWhere($criteria->expr()->eq('partnerCode', $partnerCode));
        $criteria->orderBy(['createdAt' => Criteria::DESC,]);
        $criteria->setMaxResults(1);

        /** @var \Doctrine\Common\Collections\Collection $matching */
        $matching = $this->matching($criteria);

        return $matching->isEmpty() ? null : $matching->first();
    }

    /**
     * @param AccessToken $entity
     */
    public function persist(AccessToken $entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @param AccessToken $entity
     */
    public function flush(AccessToken $entity)
    {
        $this->getEntityManager()->flush($entity);
    }
}
