<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedeemCodes
 *
 * @ORM\Table(name="redeem_codes")
 * @ORM\Entity
 */
class RedeemCodes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="campain_id", type="integer")
     */
    private $campainId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="used", type="boolean")
     */
    private $used = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     */
    private $code;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set campainId
     *
     * @param integer $campainId
     * @return RedeemCodes
     */
    public function setCampainId($campainId)
    {
        $this->campainId = $campainId;

        return $this;
    }

    /**
     * Get campainId
     *
     * @return integer 
     */
    public function getCampainId()
    {
        return $this->campainId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return RedeemCodes
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return RedeemCodes
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return RedeemCodes
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime 
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return RedeemCodes
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}
