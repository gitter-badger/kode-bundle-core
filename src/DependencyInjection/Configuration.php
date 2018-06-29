<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use KodeCms\KodeBundle\DependencyInjection\Component\Definable;
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
     * @param $extensions
     */
    public function __construct($alias, $extensions)
    {
        $this->alias = $alias;
        $this->extensions = $extensions;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($this->alias);
        $node->children();
        /** ArrayNodeDefinition $node */

        foreach ($this->extensions as $extension) {
            $class = sprintf('KodeCms\KodeBundle\%s\DependencyInjection\Definition', ucfirst(KodeCmsKodeExtension::EXT[$extension]));
            $definition = new $class();
            /** @var Definable $definition */
            $node->append($definition->getExtensionDefinition($extension));
        }

        $node->end();

        return $treeBuilder;
    }
}
