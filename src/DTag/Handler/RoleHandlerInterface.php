<?php

namespace DTag\Handler;

use DTag\Model\User\Role\UserRoleInterface;
use DTag\Exception\LogicException;

interface RoleHandlerInterface extends HandlerInterface
{
    /**
     * @param UserRoleInterface $role
     * @return bool
     */
    public function supportsRole(UserRoleInterface $role);

    public function setUserRole(UserRoleInterface $userRole);

    /**
     * @return UserRoleInterface
     * @throws LogicException
     */
    public function getUserRole();
}