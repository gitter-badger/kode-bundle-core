<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

interface Definable
{
    public function getExtensionDefinition($extension): ArrayNodeDefinition;
}
