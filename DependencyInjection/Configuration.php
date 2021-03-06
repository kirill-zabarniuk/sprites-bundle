<?php

namespace Fernando\Bundle\SpritesBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('fernando_sprites');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->booleanNode('enabled')->defaultValue('true')->end()
                ->scalarNode('java')->defaultValue('/usr/bin/java')->end()
                ->scalarNode('jar_packer')->end()
                ->scalarNode('jar_yml')->end()
                ->arrayNode('css')
                    ->children()
                        ->scalarNode('class')->defaultValue('sprite')->end()
                        ->scalarNode('filename')->defaultValue('sprite.css')->end()
                    ->end()
                ->end()
                ->arrayNode('assetic')
                    ->children()
                        ->booleanNode('enabled')->defaultValue('false')->end()
                        ->arrayNode('formula_filters')
                            ->defaultValue(array('sprite'))
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
