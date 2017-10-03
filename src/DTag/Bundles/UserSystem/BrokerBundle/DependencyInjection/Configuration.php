<?php

namespace DTag\Bundles\UserSystem\BrokerBundle\DependencyInjection;

use Rollerworks\Bundle\MultiUserBundle\DependencyInjection\Configuration as UserConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * To learn more about the user-configuration see {@link https://github.com/rollerworks/RollerworksMultiUserBundle/blob/master/docs/index.md#33-make-your-bundle-configurable}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('d_tag_user_system_broker');

        $configuration = new UserConfiguration();

        // Add the UserConfiguration tree
        // Enables everything except group
        $configuration->addUserConfig($rootNode, UserConfiguration::CONFIG_ALL ^ UserConfiguration::CONFIG_SECTION_GROUP);

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
