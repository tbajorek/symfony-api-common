<?php declare(strict_types=1);

namespace ApiCommon\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('api_common');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('app_prefix')->defaultValue('App')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}