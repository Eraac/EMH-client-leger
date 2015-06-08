<?php

namespace HIA\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session_record")
 * @ORM\Entity(repositoryClass="HIA\UserBundle\Entity\SessionRepository")
 */
class Session
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
     * @ORM\Column(name="login", type="datetime")
     */
    private $login;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logout", type="datetime", nullable=true)
     */
    private $logout;


    /**
     * @ORM\ManyToOne(targetEntity="HIA\UserBundle\Entity\User")
     */
    private $user;

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
     * Set login
     *
     * @param \DateTime $login
     *
     * @return Session
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return \DateTime
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set logout
     *
     * @param \DateTime $logout
     *
     * @return Session
     */
    public function setLogout($logout)
    {
        $this->logout = $logout;

        return $this;
    }

    /**
     * Get logout
     *
     * @return \DateTime
     */
    public function getLogout()
    {
        return $this->logout;
    }

    /**
     * Set user
     *
     * @param \HIA\UserBundle\Entity\User $user
     *
     * @return Session
     */
    public function setUser(\HIA\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \HIA\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
