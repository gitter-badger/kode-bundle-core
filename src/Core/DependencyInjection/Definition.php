<?php

namespace KodeCms\KodeBundle\Core\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Definition
{
    public function getExtensionDefinition($extension): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($extension);

        // @formatter:off
        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('short_functions')
                    ->defaultValue(false)
                ->end()
            ->end();

        // @formatter:on

        return $node;
    }
}