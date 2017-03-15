<?php

namespace LamodaB2B\Entity;

use Doctrine\ORM\Mapping as ORM;
use LamodaB2B\HTTP\Model\AccessTokenInterface;

/**
 * @ORM\Entity(repositoryClass="LamodaB2B\Entity\AccessTokenRepository")
 * @ORM\Table(name="access_token", uniqueConstraints={@ORM\UniqueConstraint(name="access_token_value_unique_idx", columns={"value"})})
 * @ORM\HasLifecycleCallbacks
 */
class AccessToken implements AccessTokenInterface
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    protected $value;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32, nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="partner_code", type="string", length=16, nullable=true)
     */
    protected $partnerCode;

    /**
     * @var int
     *
     * @ORM\Column(name="expires_at", type="integer", length=32, nullable=false)
     */
    protected $expiresAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var bool
     * @ORM\Column(name="is_deleted", type="boolean", options={"default" = 0}))
     */
    protected $isDeleted = false;

    /**
     * @return int
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param int $expiresAt
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return boolean
     */
    public function isIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     *  @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     *  @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return string
     */
    public function getPartnerCode()
    {
        return $this->partnerCode;
    }

    /**
     * @param string $partnerCode
     */
    public function setPartnerCode($partnerCode)
    {
        $this->partnerCode = $partnerCode;
    }
}
