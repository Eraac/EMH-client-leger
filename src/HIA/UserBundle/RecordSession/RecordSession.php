<?php

namespace HIA\UserBundle\RecordSession;

use Doctrine\ORM\EntityManager;
use HIA\UserBundle\Entity\Session;
use HIA\UserBundle\Entity\User;

class RecordSession
{
    protected $_manager;
    protected $_session;

    public function __construct(EntityManager $em, $session)
    {
        $this->_manager = $em;
        $this->_session = $session;
    }

    public function recordLogin(User $user)
    {
        $session = new Session();
        $session->setLogin(new \DateTime());

        $session->setUser($user);

        $this->_manager->persist($session);
        $this->_manager->flush();
    }

    public function recordLogout(User $user)
    {
        $repo = $this->_manager->getRepository("HIAUserBundle:Session");

        $sessions = $repo->getCurrentSession($user->getId());

        foreach($sessions as $session)
        {
            $session->setLogout(new \DateTime());

            $this->_manager->persist($session);
        }

        $this->_manager->flush();
    }
}
