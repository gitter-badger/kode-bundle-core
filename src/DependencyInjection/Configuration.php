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
        $rootNode->children();

        foreach ($this->extensions as $extension) {
            $class = sprintf('KodeCms\KodeBundle\%s\DependencyInjection\Definition', ucfirst(KodeCmsKodeExtension::EXT[$extension]));
            $rootNode->append((new $class())->getExtensionDefinition($extension));
        }

        $rootNode->end();

        return $treeBuilder;
    }
}
