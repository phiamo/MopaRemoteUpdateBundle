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
    protected function addLocalConfig(ArrayNodeDefinition $rootNode) {
        $rootNode
            ->children()
                ->scalarNode('composer')
                    ->defaultValue('composer.phar')
                    ->end()
            ->end()
        ;
    }
    protected function addRemotesConfig(ArrayNodeDefinition $rootNode) {
        $rootNode
            ->children()
                ->arrayNode('remotes')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('preUpdate')
                                ->defaultValue(false)
                                ->end()
                            ->scalarNode('postUpdate')
                                ->defaultValue(false)
                                ->end()
                            ->scalarNode('url')
                                ->end()
                            ->scalarNode('username')
                                ->end()
                            ->scalarNode('password')
                                ->end()
                            ->arrayNode('environments')
                                ->defaultValue(array("dev"))
                                ->requiresAtLeastOneElement()
                                ->beforeNormalization()
                                    ->ifTrue(function($v) { return !is_array($v); })
                                    ->then(function($v) { return array($v); })
                                ->end()
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('updater')
                                ->defaultValue("live")
                            ->end()
                            ->scalarNode('timeout')
                                ->defaultValue(5 * 60) // 5 min
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;
    }
}
