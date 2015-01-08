<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultValue
 *
 * @ORM\Table(name="defaultValue")
 * @ORM\Entity
 */
class DefaultValue
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
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\FormBundle\Entity\Field", inversedBy="defaultValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $field;

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
     * @return DefaultValue
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
     * Set field
     *
     * @param \HIA\FormBundle\Entity\Field $field
     * @return DefaultValue
     */
    public function setField(\HIA\FormBundle\Entity\Field $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \HIA\FormBundle\Entity\Field 
     */
    public function getField()
    {
        return $this->field;
    }
}
