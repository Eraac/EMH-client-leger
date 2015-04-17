<?php

namespace HIA\FormBundle\TopForm;

use HIA\FormBundle\Entity\Form;
use Doctrine\ORM\EntityManager;

class TopForm
{
    protected $_manager;

    public function __construct(EntityManager $em)
    {
        $this->_manager = $em;
    }

    public function getTopForm($idUser, $count = 10)
    {
        $repo = $this->_manager->getRepository('HIAFormBundle:Form');

        // Récupération des formulaires utilisés
        $forms = $repo->getUsedForm($idUser);

        // Transforme la collection doctrine en tableau php
        $forms = $this->doctrineCollectionToArray($forms);

        usort($forms, array("HIA\\FormBundle\\TopForm\\TopForm", "sort"));

        // On garde que le top selon la valeur $count
        $topForms = array_slice($forms, 0, $count);

        return $topForms;
    }

    // Transforme la collection de doctrine en tableau PHP pour effectuer le traitement avec la fonction usort
    function doctrineCollectionToArray($forms)
    {
        $formsArray = array();

        foreach ($forms as $form)
        {
            $formArray = array("name" => $form->getName(),
                "id" => $form->getId(),
                "description" => $form->getDescription(),
                "countRegistration" => $form->getCountRegistration());

            $formsArray[] = $formArray;
        }

        return $formsArray;
    }

    static function sort($form1, $form2)
    {
        // Récupère le nombre d'utilisation
        $count1 = $form1["countRegistration"];
        $count2 = $form2["countRegistration"];

        // Renvoi +1 si form1 est moins utilisé que form2
        if ($count1 < $count2)
            return +1;
        // Renvoi -1 si form1 est plus utilisé que form2
        else if ($count1 > $count2)
            return -1;
        // Renvoi 0 en cas d'égalité
        else
            return 0;
    }
}
