<?php

namespace KodeCms\KodeBundle\Core\DependencyInjection;

use KodeCms\KodeBundle\DependencyInjection\Component\Definable;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Definition implements Definable
{
    private const ALLOWED = [
        Definable::CORE,
        Definable::MOBILE,
    ];

    public function getExtensionDefinition($extension): ArrayNodeDefinition
    {
        if (!\in_array($extension, self::ALLOWED, true)) {
            throw new InvalidConfigurationException(\sprintf('Invalid extension: %s', $extension));
        }

        switch ($extension) {
            case Definable::CORE:
                return $this->getCoreDefinition();
            case Definable::MOBILE:
                return $this->getMobileDefinition();
        }
    }

    private function getCoreDefinition(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root(Definable::CORE);
        /** @var ArrayNodeDefinition $node */

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

    private function getMobileDefinition(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root(Definable::MOBILE);
        /** @var ArrayNodeDefinition $node */

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
