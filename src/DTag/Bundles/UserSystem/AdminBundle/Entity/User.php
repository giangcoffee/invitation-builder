<?php

namespace DTag\Bundles\UserSystem\AdminBundle\Entity;

use DTag\Bundles\UserBundle\Entity\User as BaseUser;
use DTag\Model\User\Role\AdminInterface;
use DTag\Model\User\UserEntityInterface;

class User extends BaseUser implements AdminInterface
{
    protected $id;
    protected $settings;

    /**
     * @return UserEntityInterface
     */
    public function getUser()
    {
        return $this;
    }
}
