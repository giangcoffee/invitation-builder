<?php

namespace DTag\Bundles\UserBundle;

use DTag\Bundles\UserBundle\DependencyInjection\Compiler\AuthenticationListenerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DTagUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AuthenticationListenerCompilerPass());
    }
}
