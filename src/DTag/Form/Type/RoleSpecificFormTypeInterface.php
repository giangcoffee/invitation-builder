<?php

namespace DTag\Form\Type;

use Symfony\Component\Form\FormTypeInterface;
use DTag\Model\User\Role\UserRoleInterface;

interface RoleSpecificFormTypeInterface extends FormTypeInterface
{
    public function setUserRole(UserRoleInterface $userRole);
}