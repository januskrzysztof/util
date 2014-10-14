<?php

namespace Tutto\Bundle\UtilBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Tutto\Bundle\UtilBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tutto_util');
        $rootNode
            ->children()
                ->arrayNode('layout_resolver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_layout')->defaultValue('::layout.html.twig')->end()
                        ->scalarNode('ajax_layout')->defaultValue('::ajax.html.twig')->end()
                        ->scalarNode('empty_layout')->defaultValue('::empty.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
