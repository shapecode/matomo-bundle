<?php

namespace Shapecode\Bundle\PiwikBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Shapecode\Bundle\PiwikBundle\DependencyInjection
 * @author  Nikita Loges
 */
class Configuration implements ConfigurationInterface
{

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('shapecode_piwik');

        $rootNode
            ->children()
            ->scalarNode('template')->defaultValue('@ShapecodePiwik/Piwik/code.html.twig')->end()
            ->scalarNode('disabled')->defaultValue('%kernel.debug%')->end()
            ->scalarNode('no_script_tracking')->defaultTrue()->end()
            ->scalarNode('host_name')->isRequired()->end()
            ->scalarNode('host_path')->defaultNull()->end()
            ->scalarNode('site_id')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }

}
