<?php

namespace DTag\Bundles\UserSystem\ModeratorBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use DTag\Model\User\Role\ModeratorInterface;
use DTag\Model\User\UserEntityInterface;

class SetModeratorRoleListener
{
    const ROLE_MODERATOR = 'ROLE_MODERATOR';

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ModeratorInterface) {
            return;
        }

        /**
         * @var UserEntityInterface $entity
         */
        $entity->setUserRoles(array(static::ROLE_MODERATOR));
    }
} 