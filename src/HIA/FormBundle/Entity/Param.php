<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Param
 *
 * @ORM\Table(name="param")
 * @ORM\Entity
 */
class Param
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\FormBundle\Entity\FieldConstraint", inversedBy="params")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fieldConstraint;


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
     * Set value
     *
     * @param string $value
     * @return Param
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set fieldConstraint
     *
     * @param \HIA\FormBundle\Entity\FieldConstraint $fieldConstraint
     * @return Param
     */
    public function setFieldConstraint(\HIA\FormBundle\Entity\FieldConstraint $fieldConstraint)
    {
        $this->fieldConstraint = $fieldConstraint;

        return $this;
    }

    /**
     * Get fieldConstraint
     *
     * @return \HIA\FormBundle\Entity\FieldConstraint 
     */
    public function getFieldConstraint()
    {
        return $this->fieldConstraint;
    }
}
