<?php

namespace HIA\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JournalAccess
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HIA\UserBundle\Entity\JournalAccessRepository")
 */
class JournalAccess
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateConnect", type="datetime")
     */
    private $dateConnect;

    /**
     * @var string
     *
     * @ORM\Column(name="loginUse", type="string", length=255)
     */
    private $loginUse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isSuccess", type="boolean")
     */
    private $isSuccess;

    /**
     * @var string
     *
     * @ORM\Column(name="reasonFail", type="string", length=255, nullable=true)
     */
    private $reasonFail;


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
     * Set dateConnect
     *
     * @param \DateTime $dateConnect
     *
     * @return JournalAccess
     */
    public function setDateConnect($dateConnect)
    {
        $this->dateConnect = $dateConnect;

        return $this;
    }

    /**
     * Get dateConnect
     *
     * @return \DateTime
     */
    public function getDateConnect()
    {
        return $this->dateConnect;
    }

    /**
     * Set loginUse
     *
     * @param string $loginUse
     *
     * @return JournalAccess
     */
    public function setLoginUse($loginUse)
    {
        $this->loginUse = $loginUse;

        return $this;
    }

    /**
     * Get loginUse
     *
     * @return string
     */
    public function getLoginUse()
    {
        return $this->loginUse;
    }

    /**
     * Set isSuccess
     *
     * @param boolean $isSuccess
     *
     * @return JournalAccess
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;

        return $this;
    }

    /**
     * Get isSuccess
     *
     * @return boolean
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Set reasonFail
     *
     * @param string $reasonFail
     *
     * @return JournalAccess
     */
    public function setReasonFail($reasonFail)
    {
        $this->reasonFail = $reasonFail;

        return $this;
    }

    /**
     * Get reasonFail
     *
     * @return string
     */
    public function getReasonFail()
    {
        return $this->reasonFail;
    }
}

