<?php

namespace KodeCms\KodeBundle\DependencyInjection\Component;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Extendable
{
    public function buildClientConfiguration(ArrayNodeDefinition $node);

    public function configureClient(ContainerBuilder $container, $clientServiceKey, array $options = []);
}
