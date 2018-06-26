<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    protected $alias;

    protected $extensions;

    /**
     * Configuration constructor.
     *
     * @param $alias
     */
    public function __construct($alias, $extensions)
    {
        $this->alias = $alias;
        $this->extensions = $extensions;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias);

        // @formatter:off
        $rootNode
            ->children()
                ->arrayNode('extensions')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->parseExtensions())
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }

    private function parseExtensions(): ArrayNodeDefinition
    {

    }
}
