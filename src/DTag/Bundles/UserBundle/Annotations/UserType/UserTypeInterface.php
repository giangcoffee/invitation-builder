<?php

namespace DTag\Bundles\UserBundle\Annotations\UserType;

interface UserTypeInterface
{
    /**
     * @return string
     */
    public function getUserClass();
}