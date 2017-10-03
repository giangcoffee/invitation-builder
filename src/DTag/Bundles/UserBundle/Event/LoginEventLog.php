<?php

namespace DTag\Bundles\UserBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class LoginEventLog extends Event implements LoginEventLogInterface
{
    /**
     *  Default action name. No other name for this event.
     */
    const LOGIN = 'LOGIN';

    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return self::LOGIN;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return null;
    }
}