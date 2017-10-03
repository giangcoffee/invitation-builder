<?php

namespace DTag\Bundles\ApiBundle\Service;

use DTag\Model\User\Role\BrokerInterface;
use DTag\Model\User\UserEntityInterface;

class JWTResponseTransformer
{
    public function transform(array $data, UserEntityInterface $user)
    {
        $data['id'] = $user->getId();
        $data['username'] = $user->getUsername();
        $data['userRoles'] = $user->getUserRoles();
        $data['enabledModules'] = $user->getEnabledModules();

        if($user instanceof BrokerInterface) {
            $data['settings'] = $user->getSettings();
            $data['moduleConfigs'] = $user->getModuleConfigs();
        }

        return $data;
    }
}