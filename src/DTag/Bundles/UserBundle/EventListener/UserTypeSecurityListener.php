<?php

namespace DTag\Bundles\UserBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DTag\Bundles\UserBundle\Annotations\UserType;
use DTag\Exception\RuntimeException;

class UserTypeSecurityListener
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    private $reader;

    public function __construct(SecurityContextInterface $securityContext = null, Reader $reader)
    {
        $this->securityContext = $securityContext;
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        /**
         * @var UserType\UserTypeInterface[] $requiredUserTypes
         */
        $requiredUserTypes = array_merge(
            $this->getConfigurations($this->reader->getClassAnnotations($object)),
            $this->getConfigurations($this->reader->getMethodAnnotations($method))
        );

        if (count($requiredUserTypes) === 0) {
            return;
        }

        $token = $this->securityContext->getToken();

        if (null === $token) {
            throw new AccessDeniedException(sprintf("You are not authenticated, a known user type is required for access."));
        }

        $user = $token->getUser();

        foreach($requiredUserTypes as $userType) {
            $userClass = $userType->getUserClass();

            if (!interface_exists($userClass) && !class_exists($userClass)) {
                throw new RuntimeException(sprintf("The user type class '%s' does not exist", $userClass));
            }

            if (!$user instanceof $userClass) {
                throw new AccessDeniedException(sprintf("You do not have the required user type. A user type of '%s' is required.", $userClass));
            }

            unset($userType, $userClass);
        }
    }

    protected function getConfigurations(array $annotations)
    {
        $configurations = array();

        foreach ($annotations as $configuration) {
            if ($configuration instanceof UserType\UserTypeInterface) {
                $configurations[] = $configuration;
            }
        }

        return $configurations;
    }
}