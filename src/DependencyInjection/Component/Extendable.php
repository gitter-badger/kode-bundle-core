<?php

namespace KodeCms\KodeBundle\DependencyInjection\Component;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Extendable
{
    public function buildClientConfiguration(NodeDefinition $node);

    public function configureClient(ContainerBuilder $container, $clientServiceKey, array $options = []);
}
