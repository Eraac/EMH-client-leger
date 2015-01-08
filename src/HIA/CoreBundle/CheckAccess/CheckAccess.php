<?php

namespace HIA\CoreBundle\CheckAccess;

use Doctrine\ORM\EntityManager;

class CheckAccess
{
    protected $_manager;

    public function __construct(EntityManager $em)
    {
        $this->_manager = $em;
    }

    // Renvoi un boolen si l'utilisateur à le droit d'utiliser un formulaire
    public function canUse($idUser, $idForm)
    {
        $repoForm = $this->_manager->getRepository("HIAFormBundle:Form");

        $hasAccess = $repoForm->canUse($idUser, $idForm);

        return ($hasAccess > 0) ? true : false;
    }

    // Renvoi un boolen si l'utilisateur à le droit de lire un enregistrement
    public function canRead($idUser, $idRegistration)
    {
        $repoRegistration = $this->_manager->getRepository("HIAFormBundle:Registration");

        $hasAccess = $repoRegistration->canRead($idUser, $idRegistration);

        return ($hasAccess > 0) ? true : false;
    }
}