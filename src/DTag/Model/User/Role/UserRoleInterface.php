<?php

namespace DTag\Model\User\Role;

use DTag\Model\User\UserEntityInterface;

interface UserRoleInterface
{
    /**
     * @return UserEntityInterface
     */
    public function getUser();

    /**
     * @return int|null
     */
    public function getId();
}