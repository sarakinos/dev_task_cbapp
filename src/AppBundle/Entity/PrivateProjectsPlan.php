<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrivateProjectsPlan
 *
 * @ORM\Table(name="private_projects_users")
 * @ORM\Entity
 */
class PrivateProjectsPlan
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_allowed", type="integer")
     */
    private $numberAllowed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime")
     */
    private $expirationDate;


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
     * Set user
     *
     * @param integer $user
     * @return PrivateProjectsPlan
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set numberAllowed
     *
     * @param integer $numberAllowed
     * @return PrivateProjectsPlan
     */
    public function setNumberAllowed($numberAllowed)
    {
        $this->numberAllowed = $numberAllowed;

        return $this;
    }

    /**
     * Get numberAllowed
     *
     * @return integer 
     */
    public function getNumberAllowed()
    {
        return $this->numberAllowed;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     * @return PrivateProjectsPlan
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}
