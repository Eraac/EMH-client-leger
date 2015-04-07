<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Register
 *
 * @ORM\Table(name="register")
 * @ORM\Entity(repositoryClass="HIA\FormBundle\Entity\RegisterRepository")
 */
class Register
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
     * @ORM\Column(name="data", type="text")
     */
    private $data;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\FormBundle\Entity\Field")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id",  onDelete="SET NULL")
     */
    private $field;

    /**
     * @ORM\ManyToOne(targetEntity="HIA\FormBundle\Entity\Registration", inversedBy="registers", cascade={"persist"})
     */
    private $registration;

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
     * Set data
     *
     * @param string $data
     * @return Register
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set field
     *
     * @param \HIA\FormBundle\Entity\Field $field
     * @return Register
     */
    public function setField(\HIA\FormBundle\Entity\Field $field = null)
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

    /**
     * Set registration
     *
     * @param \HIA\FormBundle\Entity\Registration $registration
     * @return Register
     */
    public function setRegistration(\HIA\FormBundle\Entity\Registration $registration = null)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * Get registration
     *
     * @return \HIA\FormBundle\Entity\Registration 
     */
    public function getRegistration()
    {
        return $this->registration;
    }
}
