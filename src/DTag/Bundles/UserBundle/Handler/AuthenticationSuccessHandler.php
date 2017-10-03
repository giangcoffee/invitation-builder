<?php

namespace DTag\Bundles\UserBundle\Handler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use DTag\Bundles\UserBundle\Event\LoginEventLog;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var AuthenticationSuccessHandlerInterface
     */
    protected $wrappedSuccessHandler;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var string
     */
    protected $successEventName;

    public function __construct(AuthenticationSuccessHandlerInterface $wrappedSuccessHandler)
    {
        $this->wrappedSuccessHandler = $wrappedSuccessHandler;
    }


    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // Step 1. Invoke success handler
        $response  = $this->wrappedSuccessHandler->onAuthenticationSuccess($request, $token);

        // Step 2. Dispatch success event if any
        if( $this->eventDispatcher != null && $this->successEventName != null) {
            $this->eventDispatcher->dispatch($this->successEventName, new LoginEventLog());
        }

        return $response;

    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $successEventName
     */
    public function setSuccessEventName($successEventName)
    {
        $this->successEventName = $successEventName;
    }

} 