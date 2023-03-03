<?php declare(strict_types=1);

namespace ApiCommon\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use ApiCommon\Model\Configuration as ConfigurationModel;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('api_common');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('app_prefix')->defaultValue('App')->end()
                ->arrayNode('installer')
                    ->children()
                        ->enumNode('sort_mode')
                            ->values([
                                ConfigurationModel::INSTALLER_SORT_MODE_DEPENDENCIES,
                                ConfigurationModel::INSTALLER_SORT_MODE_ORDER
                            ])->defaultValue(ConfigurationModel::INSTALLER_SORT_MODE_DEPENDENCIES)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}