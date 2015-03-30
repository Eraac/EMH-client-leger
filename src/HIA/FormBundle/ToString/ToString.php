<?php

namespace HIA\FormBundle\ToString;

use Doctrine\ORM\EntityManager;

class ToString
{
    protected $_manager;

    public function __construct(EntityManager $em)
    {
        $this->_manager = $em;
    }

    // Convertir les données en string si cela est nécessaire
    public function convertToString($data, $isChoice = false)
    {
        // Si data est un objet DateTime
        if ($data instanceof \DateTime)
            return $data->format('d-m-Y');

        // Si la data corresponds à une liste de choix
        else if ($isChoice)
        {
            // On récupère le repository de DefaultValue
            $repoDefaultValue = $this->_manager->getRepository("HIAFormBundle:DefaultValue");

            // On init string vide
            $string = "";

            // Si la data est un tableau
            if (is_array($data))
            {
                // On parcourt le tableau
                foreach ($data as $id)
                {
                    // On récupère la valeur correponds au choix
                    $string .= $repoDefaultValue->find((int) $id)->getValue() . "\n";
                }                
            }
            else
            {
                // On récupère la valeur correponds au choix
                $string = $repoDefaultValue->find((int) $data)->getValue();
            }
            
            return $string;
        }
        else
            return $data;
    }
}
