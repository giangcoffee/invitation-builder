<?php

namespace DTag\Bundles\UserBundle\Request\UserSystem;

use Rollerworks\Bundle\MultiUserBundle\EventListener\AuthenticationListener as BaseAuthenticationListener;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class AuthenticationListener extends BaseAuthenticationListener
{
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->discriminate($user);
    }

    public static function getSubscribedEvents()
    {
        $events = array(
            AuthenticationEvents::AUTHENTICATION_SUCCESS => array('onAuthenticationSuccess', 255),
        );

        return array_merge($events, parent::getSubscribedEvents());
    }
}
