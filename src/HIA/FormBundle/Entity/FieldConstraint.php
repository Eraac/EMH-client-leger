<?php

namespace HIA\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Lenght;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;


/**
 * FieldConstraint
 *
 * @ORM\Table(name="fieldConstraint")
 * @ORM\Entity
 */
class FieldConstraint
{
    public static $_TYPES = array("NOTNULL" => 1, "MAIL" => 2, "LENGTH" => 3, "URL" => 4,
                                "REGEX" => 5, "RANGE" => 6, "NOTEQUAL" => 7, "DATE" => 8,
                                "DATETIME" => 9, "TIME" => 10, "USERPASSWORD" => 11, "LOWER" => 12,
                                "LOWEROREQUAL" => 13, "HIGHER" => 14, "HIGHEROREQUAL" => 15);

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
     * @ORM\ManyToMany(targetEntity="HIA\FormBundle\Entity\Field", mappedBy="fieldConstraints")
     * @ORM\JoinTable(name="constrained")
     */
    private $fields;

    /**
     * @ORM\OneToMany(targetEntity="HIA\FormBundle\Entity\Param", mappedBy="fieldConstraint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $params;

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
     * @return FieldConstraint
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
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->params = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fields
     *
     * @param \HIA\FormBundle\Entity\Field $fields
     * @return FieldConstraint
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

    public function getConstraintClass()
    {
        $constraint = null;
        $paramsConstraint = $this->params;

        switch($this->type)
        {
            case self::$_TYPES['MAIL']:
                $message = "{{ value }} n'est pas un email valide";
                $constraint = new Email(array('message' => $message));
            break;

            case self::$_TYPES['LENGTH']:
                $minMessage = "Le champs doit faire mininum {{ limit }} de longeur";
                $maxMessage = "Le champs doit faire maximum {{ limit }} de longeur";

                $values = array($paramsConstraint[0]->getValue());

                if (isset($paramsConstraint[1]))
                    $values[] = $paramsConstraint[1]->getValue();

                $options = array('min' => min($values), 'minMessage' => $minMessage, 'maxMessage' => $maxMessage);

                if (isset($values[1]))
                    $options['max'] = max($values);

                $constraint = new Lenght($options);
            break;

            case self::$_TYPES['URL']:
                $message = "Cette valeur doit-être une URL valide";

                $constraint = new Url(array('message' => $message));
            break;

            case self::$_TYPES['REGEX']:
                $message = "La valeur ne rentre pas dans les conditions requises";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new Regex(array('pattern' => $value, 'message' => $message));
            break;

            case self::$_TYPES['RANGE']:
                $minMessage = "La valeur doit-être supérieur à {{ limit }}";
                $maxMessage = "La valeur doit-être inférieur à {{ limit }}";

                $values = array($paramsConstraint[0]->getValue(), $paramsConstraint[1]->getValue());

                $constraint = new Range(array('min' => min($values), 'max' => max($values), 'minMessage' => $minMessage, 'maxMessage' => $maxMessage));
            break;

            case self::$_TYPES['NOTEQUAL']:
                $message = "La valeur doit-être différente de {{ compared_value }}";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new NotEqualTo(array('value' => $value, 'message' => $message));
            break;

            case self::$_TYPES['DATE']:
                $message = "Le champs n'est pas une date valide";

                $constraint = new Date(array('message' => $message));
            break;

            case self::$_TYPES['DATETIME']:
                $message = "Le champs n'est pas une date et heure valide";

                $constraint = new DateTime(array('message' => $message));
            break;

            case self::$_TYPES['TIME']:
                $message = "Le champs n'est pas une heure valide";

                $constraint = new Time(array('message' => $message));
            break;

            case self::$_TYPES['USERPASSWORD']:
                $message = "Mot de passe invalide";

                $constraint = new UserPassword(array('message' => $message));
            break;

            case self::$_TYPES['LOWER']:
                $message = "La valeur doit-être inférieur à {{ compared_value }}";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new LowerThan(array('value' => $value, 'message' => $message));
            break;

            case self::$_TYPES['LOWEROREQUAL']:
                $message = "La valeur doit-être inférieur ou égale à {{ compared_value }}";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new LowerThan(array('value' => $value, 'message' => $message));
            break;

            case self::$_TYPES['HIGHER']:
                $message = "La valeur doit-être supérieur à {{ compared_value }}";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new GreaterThan(array('value' => $value, 'message' => $message));
            break;

            case self::$_TYPES['HIGHEROREQUAL']:
                $message = "La valeur doit-être supérieur ou égale à {{ compared_value }}";

                $value = $paramsConstraint[0]->getValue();

                $constraint = new GreaterThan(array('value' => $value, 'message' => $message));
            break;
        }

        return $constraint;
    }

    /**
     * Add params
     *
     * @param \HIA\FormBundle\Entity\Param $params
     * @return FieldConstraint
     */
    public function addParam(\HIA\FormBundle\Entity\Param $params)
    {
        $this->params[] = $params;

        return $this;
    }

    /**
     * Remove params
     *
     * @param \HIA\FormBundle\Entity\Param $params
     */
    public function removeParam(\HIA\FormBundle\Entity\Param $params)
    {
        $this->params->removeElement($params);
    }

    /**
     * Get params
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParams()
    {
        return $this->params;
    }
}
