<?php

namespace DTag\Form\Type;

use Symfony\Component\Form\AbstractType;
use DTag\Model\User\Role\UserRoleInterface;

abstract class AbstractRoleSpecificFormType extends AbstractType implements RoleSpecificFormTypeInterface
{
    /**
     * @var UserRoleInterface
     */
    protected $userRole;

    public function setUserRole(UserRoleInterface $userRole)
    {
        $this->userRole = $userRole;
    }
}