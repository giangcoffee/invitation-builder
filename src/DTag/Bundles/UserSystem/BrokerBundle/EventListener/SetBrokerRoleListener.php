<?php

namespace DTag\Bundles\UserSystem\BrokerBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use DTag\Model\User\Role\BrokerInterface;
use DTag\Model\User\UserEntityInterface;

class SetBrokerRoleListener
{
    const ROLE_BROKER = 'ROLE_BROKER';

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof BrokerInterface) {
            return;
        }

        /**
         * @var UserEntityInterface $entity
         */

        $entity->setUserRoles(array(static::ROLE_BROKER));
    }
} 