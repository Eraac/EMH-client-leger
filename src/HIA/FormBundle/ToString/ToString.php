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
    
    public function convertToString($data, $isChoice = false)
    {
        if ($data instanceof \DateTime)
            return $data->format('d-m-Y');
        else if ($isChoice)
        {
            $repoDefaultValue = $this->_manager->getRepository("HIAFormBundle:DefaultValue");
            
            $string = "";
            
            if (is_array($data))
            {                
                foreach ($data as $id)
                {
                    $string .= $repoDefaultValue->find((int) $id)->getValue() . "\n";
                }                
            }
            else
            {
                $string = $repoDefaultValue->find((int) $data)->getValue();
            }
            
            return $string;
        }
        else
            return $data;
    }
}