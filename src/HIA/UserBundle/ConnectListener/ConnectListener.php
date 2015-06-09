<?php

namespace HIA\UserBundle\ConnectListener;

use HIA\UserBundle\RecordAuthentification\RecordAuthentification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use HIA\UserBundle\Entity\User;

class ConnectListener
{
    protected $_recordAuthentification;

    public function __construct(RecordAuthentification $recordAuthentification)
    {
        $this->_recordAuthentification = $recordAuthentification;
    }

    public function processLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $this->_recordAuthentification->recordLogin($user);
    }

    public function processLoginFailure(AuthenticationFailureEvent $event)
    {
        $username = $event->getAuthenticationToken()->getUser();

        $this->_recordAuthentification->recordLogout($username);
    }
}