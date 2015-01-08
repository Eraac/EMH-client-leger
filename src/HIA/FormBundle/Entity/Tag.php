<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="HIA\FormBundle\Entity\TagRepository")
 */
class Tag
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\FormBundle\Entity\Form", mappedBy="tags")
     * @ORM\JoinTable(name="categorizing")
     */
    private $forms;

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
     * @return Tag
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
     * Constructor
     */
    public function __construct()
    {
        $this->forms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add forms
     *
     * @param \HIA\FormBundle\Entity\Form $forms
     * @return Tag
     */
    public function addForm(\HIA\FormBundle\Entity\Form $forms)
    {
        $this->forms[] = $forms;

        return $this;
    }

    /**
     * Remove forms
     *
     * @param \HIA\FormBundle\Entity\Form $forms
     */
    public function removeForm(\HIA\FormBundle\Entity\Form $forms)
    {
        $this->forms->removeElement($forms);
    }

    /**
     * Get forms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForms()
    {
        return $this->forms;
    }
}
