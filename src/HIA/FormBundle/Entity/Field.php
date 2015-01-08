<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Field
 *
 * @ORM\Table(name="field")
 * @ORM\Entity(repositoryClass="HIA\FormBundle\Entity\FieldRepository")
 */
class Field
{
    public static $_TYPES = array("DATE" => 1, "DATETIME" => 2, "EMAIL" => 3, "NUMBER" => 4,
                                "TEXT" => 5, "TIME" => 6, "URL" => 7, "RADIO" => 8, "TEXTAREA" => 9);

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
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="multiple", type="boolean")
     */
    private $multiple;

    /**
     * @var string
     *
     * @ORM\Column(name="labelField", type="string", length=255)
     */
    private $labelField;

    /**
     * @var string
     *
     * @ORM\Column(name="placeholder", type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @var string
     *
     * @ORM\Column(name="helpText", type="string", length=255, nullable=true)
     */
    private $helpText;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRequired", type="boolean")
     */
    private $isRequired;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\FormBundle\Entity\FieldConstraint", inversedBy="fields")
     * @ORM\JoinTable(name="constrained")
     */
    private $fieldConstraints;

    /**
     * @ORM\ManyToMany(targetEntity="HIA\FormBundle\Entity\Form", inversedBy="fields", cascade={"persist"})
     * @ORM\JoinTable(name="contains")
     */
    private $forms;
    
    /**
     * @ORM\OneToMany(targetEntity="HIA\FormBundle\Entity\DefaultValue", mappedBy="field")
     */
    private $defaultValues;

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
     * Set type
     *
     * @param integer $type
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set multiple
     *
     * @param boolean $multiple
     * @return Field
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Get multiple
     *
     * @return boolean 
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Set labelField
     *
     * @param string $labelField
     * @return Field
     */
    public function setLabelField($labelField)
    {
        $this->labelField = $labelField;

        return $this;
    }

    /**
     * Get labelField
     *
     * @return string 
     */
    public function getLabelField()
    {
        return $this->labelField;
    }

    /**
     * Set placeholder
     *
     * @param string $placeholder
     * @return Field
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return string 
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set helpText
     *
     * @param string $helpText
     * @return Field
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * Get helpText
     *
     * @return string 
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldConstraints = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fieldConstraints
     *
     * @param \HIA\FormBundle\Entity\FieldConstraint $fieldConstraints
     * @return Field
     */
    public function addFieldConstraint(\HIA\FormBundle\Entity\FieldConstraint $fieldConstraints)
    {
        $this->fieldConstraints[] = $fieldConstraints;

        return $this;
    }

    /**
     * Remove fieldConstraints
     *
     * @param \HIA\FormBundle\Entity\FieldConstraint $fieldConstraints
     */
    public function removeFieldConstraint(\HIA\FormBundle\Entity\FieldConstraint $fieldConstraints)
    {
        $this->fieldConstraints->removeElement($fieldConstraints);
    }

    /**
     * Get fieldConstraints
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFieldConstraints()
    {
        return $this->fieldConstraints;
    }

    /**
     * Add forms
     *
     * @param \HIA\FormBundle\Entity\Form $forms
     * @return Field
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

    /**
     * Retourne le type html en string
     *
     * @return string
     */
    public function getHtmlType()
    {
        switch($this->type)
        {
            case self::$_TYPES['DATE']:
                return "date";
            break;

            case self::$_TYPES['DATETIME']:
                return "datetime";
            break;

            case self::$_TYPES['EMAIL']:
                return "email";
            break;

            case self::$_TYPES['NUMBER']:
                return "integer";
            break;

            case self::$_TYPES['TEXT']:
                return "text";
            break;

            case self::$_TYPES['TIME']:
                return "time";
            break;

            case self::$_TYPES['URL']:
                return "url";
            break;

            case self::$_TYPES['RADIO']:
                return "choice";
            break;

            case self::$_TYPES['TEXTAREA']:
                return "textarea";
            break;

            default:
                return "text";
        }
    }

    /**
     * Set isRequired
     *
     * @param boolean $isRequired
     * @return Field
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * Get isRequired
     *
     * @return boolean
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * Add defaultValues
     *
     * @param \HIA\FormBundle\Entity\DefaultValue $defaultValues
     * @return Field
     */
    public function addDefaultValue(\HIA\FormBundle\Entity\DefaultValue $defaultValues)
    {
        $this->defaultValues[] = $defaultValues;

        return $this;
    }

    /**
     * Remove defaultValues
     *
     * @param \HIA\FormBundle\Entity\DefaultValue $defaultValues
     */
    public function removeDefaultValue(\HIA\FormBundle\Entity\DefaultValue $defaultValues)
    {
        $this->defaultValues->removeElement($defaultValues);
    }

    /**
     * Get defaultValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }
}
