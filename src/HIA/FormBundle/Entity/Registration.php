<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registration
 *
 * @ORM\Table(name="registration")
 * @ORM\Entity(repositoryClass="HIA\FormBundle\Entity\RegistrationRepository")
 */
class Registration
{
    public static $_STATUS = array("NEW" => 1, "PENDING" => 2, "VALIDATE" => 3, "ACCEPT" => 4, "REFUSE" => 5);

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
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationDate", type="datetime")
     */
    private $registrationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="userComment", type="text", nullable=true)
     */
    private $userComment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validationDate", type="datetime", nullable=true)
     */
    private $validationDate;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\FormBundle\Entity\Form")
     */
    private $form;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\UserBundle\Entity\User")
     */
    private $userValidate; // L'utilisateur qui valide le formulaire

    /**
     * @ORM\ManyToOne(targetEntity="HIA\UserBundle\Entity\User")
     */
    private $userSubmit; // L'utilisateur qui à soumit le formulaire

    /**
     * @ORM\OneToMany(targetEntity="HIA\FormBundle\Entity\Register", mappedBy="registration", cascade={"persist"})
     */
    private $registers;

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
     * Set status
     *
     * @param integer $status
     * @return Registration
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return Registration
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set userComment
     *
     * @param string $userComment
     * @return Registration
     */
    public function setUserComment($userComment)
    {
        $this->userComment = $userComment;

        return $this;
    }

    /**
     * Get userComment
     *
     * @return string 
     */
    public function getUserComment()
    {
        return $this->userComment;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set validationDate
     *
     * @param \DateTime $validationDate
     * @return Registration
     */
    public function setValidationDate($validationDate)
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    /**
     * Get validationDate
     *
     * @return \DateTime 
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * Set form
     *
     * @param \HIA\FormBundle\Entity\Form $form
     * @return Registration
     */
    public function setForm(\HIA\FormBundle\Entity\Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return \HIA\FormBundle\Entity\Form 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set userValidate
     *
     * @param \HIA\UserBundle\Entity\User $userValidate
     * @return Registration
     */
    public function setUserValidate(\HIA\UserBundle\Entity\User $userValidate = null)
    {
        $this->userValidate = $userValidate;

        return $this;
    }

    /**
     * Get userValidate
     *
     * @return \HIA\UserBundle\Entity\User 
     */
    public function getUserValidate()
    {
        return $this->userValidate;
    }

    /**
     * Set userSubmit
     *
     * @param \HIA\UserBundle\Entity\User $userSubmit
     * @return Registration
     */
    public function setUserSubmit(\HIA\UserBundle\Entity\User $userSubmit = null)
    {
        $this->userSubmit = $userSubmit;

        return $this;
    }

    /**
     * Get userSubmit
     *
     * @return \HIA\UserBundle\Entity\User 
     */
    public function getUserSubmit()
    {
        return $this->userSubmit;
    }

    /**
     * Add registers
     *
     * @param \HIA\FormBundle\Entity\Register $registers
     * @return Registration
     */
    public function addRegister(\HIA\FormBundle\Entity\Register $registers)
    {
        $this->registers[] = $registers;

        $registers->setRegistration($this);

        return $this;
    }

    /**
     * Remove registers
     *
     * @param \HIA\FormBundle\Entity\Register $registers
     */
    public function removeRegister(\HIA\FormBundle\Entity\Register $registers)
    {
        $this->registers->removeElement($registers);
    }

    /**
     * Get registers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegisters()
    {
        return $this->registers;
    }

    public function isNew()
    {
        return ($this->status == self::$_STATUS['NEW']);
    }

    public function isPending()
    {
        return ($this->status == self::$_STATUS['PENDING']);
    }

    public function isValidate()
    {
        return ($this->status == self::$_STATUS['VALIDATE']);
    }

    public function isAccept()
    {
        return ($this->status == self::$_STATUS['ACCEPT']);
    }

    public function isRefuse()
    {
        return ($this->status == self::$_STATUS['REFUSE']);
    }
}
