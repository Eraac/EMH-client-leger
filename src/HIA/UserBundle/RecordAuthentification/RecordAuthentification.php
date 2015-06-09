<?php

namespace HIA\UserBundle\RecordAuthentification;

use Doctrine\ORM\EntityManager;
use HIA\UserBundle\Entity\JournalAccess;
use HIA\UserBundle\Entity\User;

class RecordAuthentification
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
        $journal = new JournalAccess();

        $journal->setIsSuccess(true);
        $journal->setDateConnect(new \DateTime());
        $journal->setLoginUse($user->getUsername());
        $journal->setReasonFail(null);

        $this->_manager->persist($journal);
        $this->_manager->flush();
    }

    public function recordLogout($username)
    {
        $repo = $this->_manager->getRepository("HIAUserBundle:User");

        $userExist = $repo->isExist($username);
        $reason = "unknown";

        if ($userExist)
            $reason = "Mauvais mot de passe";
        else
            $reason = "Compte inconnu";

        $this->_session->getFlashBag()->add(
            'error',
            $reason
        );

        $journal = new JournalAccess();

        $journal->setIsSuccess(false);
        $journal->setDateConnect(new \DateTime());
        $journal->setLoginUse($username);
        $journal->setReasonFail($reason);

        $this->_manager->persist($journal);

        $this->_manager->flush();
    }
}
