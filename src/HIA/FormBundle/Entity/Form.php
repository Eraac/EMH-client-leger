<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Form
 *
 * @ORM\Table(name="form")
 * @ORM\Entity(repositoryClass="HIA\FormBundle\Entity\FormRepository")
 */
class Form
{
    // Status des formulaires
    public static $_STATUS = array("DISABLE" => 1, "ENABLE" => 2, "DEMAND" => 3);

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text", nullable=true)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="important", type="text", nullable=true)
     */
    private $important;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="text")
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\FormBundle\Entity\Tag", inversedBy="forms")
     * @ORM\JoinTable(name="categorizing")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="HIA\FormBundle\Entity\Field", mappedBy="form", cascade={"persist"})
     */
    private $fields;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\UserBundle\Entity\UserGroup")
     * @ORM\JoinTable(name="writer")
     */
    private $writers;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\UserBundle\Entity\UserGroup")
     * @ORM\JoinTable(name="reader")
     */
    private $readers;

    /**
     * @ORM\OneToMany(targetEntity="HIA\FormBundle\Entity\Registration", mappedBy="form")
     */
    private $registrations;

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
     * Set name
     *
     * @param string $name
     * @return Form
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Form
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Form
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set important
     *
     * @param string $important
     * @return Form
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * Get important
     *
     * @return string 
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Form
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Form
     */
    public function setDateCreate(\Datetime $dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set author
     *
     * @param \HIA\UserBundle\Entity\User $author
     * @return Form
     */
    public function setAuthor(\HIA\UserBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \HIA\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fields       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->writers      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->readers      = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tags
     *
     * @param \HIA\FormBundle\Entity\Tag $tags
     * @return Form
     */
    public function addTag(\HIA\FormBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \HIA\FormBundle\Entity\Tag $tags
     */
    public function removeTag(\HIA\FormBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add fields
     *
     * @param \HIA\FormBundle\Entity\Field $fields
     * @return Form
     */
    public function addField(\HIA\FormBundle\Entity\Field $fields)
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * Remove fields
     *
     * @param \HIA\FormBundle\Entity\Field $fields
     */
    public function removeField(\HIA\FormBundle\Entity\Field $fields)
    {
        $this->fields->removeElement($fields);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Add writers
     *
     * @param \HIA\UserBundle\Entity\UserGroup $writers
     * @return Form
     */
    public function addWriter(\HIA\UserBundle\Entity\UserGroup $writers)
    {
        $this->writers[] = $writers;

        return $this;
    }

    /**
     * Remove writers
     *
     * @param \HIA\UserBundle\Entity\UserGroup $writers
     */
    public function removeWriter(\HIA\UserBundle\Entity\UserGroup $writers)
    {
        $this->writers->removeElement($writers);
    }

    /**
     * Get writers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * Add readers
     *
     * @param \HIA\UserBundle\Entity\UserGroup $readers
     * @return Form
     */
    public function addReader(\HIA\UserBundle\Entity\UserGroup $readers)
    {
        $this->readers[] = $readers;

        return $this;
    }

    /**
     * Remove readers
     *
     * @param \HIA\UserBundle\Entity\UserGroup $readers
     */
    public function removeReader(\HIA\UserBundle\Entity\UserGroup $readers)
    {
        $this->readers->removeElement($readers);
    }

    /**
     * Get readers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReaders()
    {
        return $this->readers;
    }


    public function isDemand()
    {
        return ($this->status == self::$_STATUS['DEMAND']);
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Form
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add registration
     *
     * @param \HIA\FormBundle\Entity\Registration $registration
     *
     * @return Form
     */
    public function addRegistration(\HIA\FormBundle\Entity\Registration $registration)
    {
        $this->registrations[] = $registration;

        return $this;
    }

    /**
     * Remove registration
     *
     * @param \HIA\FormBundle\Entity\Registration $registration
     */
    public function removeRegistration(\HIA\FormBundle\Entity\Registration $registration)
    {
        $this->registrations->removeElement($registration);
    }

    /**
     * Get registrations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }

    public function getCountRegistration()
    {
        return $this->registrations->count();
    }
}
