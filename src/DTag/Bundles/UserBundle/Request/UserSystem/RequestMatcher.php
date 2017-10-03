<?php

namespace DTag\Bundles\UserBundle\Request\UserSystem;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        // do not attempt to match any urls to any user systems
        // multi-user-bundle requires either a request matcher or a path/host for every user system
        // this is a work-around for that so we don't have to specify an arbitrary path/host

        // in reality, this would never be called, since by the time the roller works request listener is called
        // our request listener with a higher priority has already set the correct user with the discriminator
        // this only exists to make sure the compiler passes and configuration for roller works bundle does not throw any errors

        return false;
    }
}