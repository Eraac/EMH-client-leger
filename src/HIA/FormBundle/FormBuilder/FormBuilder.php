<?php

namespace HIA\FormBundle\FormBuilder;

use HIA\FormBundle\Entity\Form;
use HIA\FormBundle\Entity\Field;
use HIA\FormBundle\Entity\FieldConstraint;
use Symfony\Component\Validator\Constraints\Range;

class FormBuilder
{
    protected $_formFactory;

    public function __construct($formFactory)
    {
        $this->_formFactory = $formFactory;
    }

    public function buildForm(\HIA\FormBundle\Entity\Form $form)
    {
        $htmlForm = $this->_formFactory->createBuilder();

        $fields = $form->getFields();

        // TODO [BUG] Les champs ne sont pas dans le même ordre que la base de données
        foreach ($fields as $field)
        {
            $type       = $field->getHtmlType();
            $label      = $field->getLabelField();

            $options = array();

            $options['required']    = $field->getIsRequired();
            $options['constraints'] = $this->getConstraints($field->getFieldConstraints());
            $options['attr']        = array('help_block' => $field->getHelpText());
            $options['label']       = $field->getLabelHuman();
            
            if ("choice" == $type)
            {
                $options['multiple'] = $field->getMultiple();
                $options['expanded'] = false;
                
                $options['choices'] = array();
                
                $defaultValues = $field->getDefaultValues();
                
                foreach ($defaultValues as $defaultValue)
                {
                    $options['choices'][$defaultValue->getId()] = $defaultValue->getValue();
                }
            }
            else
            {
                $defaultValue = $field->getDefaultValues()[0]; // IMPORTANT (pour avoir le rouge) Attention seulement depuis PHP 5.4

                if (null !== $defaultValue)
                    $options['data'] = $defaultValue->getValue();
            }

            $htmlForm->add($label, $type, $options);
        }

        $htmlForm->add("remarque", "textarea", array('label' => "Remarque", 'attr' => array('help_block' => "Une remarque générale sur votre enregistrement, si cela est nécessaire."), 'required' => false));
        $htmlForm->add("Envoyer", "submit", array('attr' => array('class' => "pull-right btn-success")));

        return $htmlForm;
    }

    public function getConstraints($constraints)
    {
        $listConstraint = array();

        foreach($constraints as $constraint)
        {
            $classConstraint = $constraint->getConstraintClass();

            if ($classConstraint !== null)
            {
                $listConstraint[] = $classConstraint;
            }
        }

        return $listConstraint;
    }
}