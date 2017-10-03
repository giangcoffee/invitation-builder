<?php

namespace DTag\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use DTag\Exception\InvalidUserRoleException;

Class RoleToUserEntityTransformer implements DataTransformerInterface
{
    public function transform($role)
    {
        if (!$role) {
            return null;
        }

        return $role;
    }

    public function reverseTransform($user)
    {
        if (!$user) {
            return null;
        }

        try {
            return $user;
        }
        catch (InvalidUserRoleException $e) {
            throw new TransformationFailedException(sprintf(
                'The user could not be converted to a publisher role'
            ));
        }
    }
}