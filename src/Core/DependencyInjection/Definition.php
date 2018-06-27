<?php

namespace KodeCms\KodeBundle\Core\DependencyInjection;

use KodeCms\KodeBundle\DependencyInjection\Definable;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Definition implements Definable
{
    public function getExtensionDefinition($extension): ArrayNodeDefinition
    {
        switch ($extension) {
            case 'core':
                return $this->getCoreDefinition($extension);
            case 'mobile':
                return $this->getMobilDefinition($extension);
        }
    }

    private function getCoreDefinition($extension): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($extension);

        // @formatter:off
        $node
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('short_functions')
                    ->defaultValue(false)
                ->end()
            ->end();

        // @formatter:on

        return $node;
    }

    private function getMobilDefinition($extension): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($extension);

        // @formatter:off
        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()

            ->end();

        // @formatter:on

        return $node;
    }
}
