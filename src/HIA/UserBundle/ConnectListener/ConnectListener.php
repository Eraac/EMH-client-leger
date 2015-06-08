<?php

namespace HIA\UserBundle\ConnectListener;

use HIA\UserBundle\RecordSession\RecordSession;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use HIA\UserBundle\Entity\User;

class ConnectListener
{
    protected $_recordSession;

    public function __construct(RecordSession $recordSession)
    {
        $this->_recordSession = $recordSession;
    }

    public function processLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $this->_recordSession->recordLogin($user);
    }
}