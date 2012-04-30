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
        //$this->addRemotesConfig($rootNode);
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
    protected function addRemoteConfig(ArrayNodeDefinition $rootNode){
        $rootNode
            ->children()
                ->arrayNode('remotes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('render_fieldset')
                            ->defaultValue(true)
                            ->end()
                        ->booleanNode('show_legend')
                            ->defaultValue(true)
                            ->end()
                        ->booleanNode('show_child_legend')
                            ->defaultValue(false)
                            ->end()
                        ->scalarNode('error_type')
                            ->defaultValue('inline')
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;
    }
}
