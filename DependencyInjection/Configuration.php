<?php
namespace Mopa\Bundle\RemoteUpdateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mopa_remote_update');
        $this->addRemotesConfig($rootNode);
        $this->addLocalConfig($rootNode);
        return $treeBuilder;
    }
    protected function addLocalConfig(ArrayNodeDefinition $rootNode){
        $rootNode
            ->children()
                ->scalarNode('updater_role')
                    ->defaultValue('ROLE_UPDATER')
                    ->end()
            ->end()
        ;
    }
    protected function addRemotesConfig(ArrayNodeDefinition $rootNode){
        $rootNode
            ->children()
                ->arrayNode('remotes')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('variable')
                        ->validate()
                            ->ifTrue(function($v) { return !is_array($v); })
                            ->thenInvalid('The mopa_remote_update.remotes config %s must be an array.')
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;
    }
}
