<?php

namespace KodeCms\KodeBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use KodeCms\KodeBundle\DependencyInjection\Component\Definable;
use KodeCms\KodeBundle\DependencyInjection\KodeCmsKodeExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

if (!\defined('KODE')) {
    \define('KODE', 'kode_cms_kode');
}

class KodeCmsKodeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            return new KodeCmsKodeExtension();
        }

        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        foreach (KodeCmsKodeExtension::FIXED as $extension) {
            $className = sprintf('KodeCms\KodeBundle\%s\DependencyInjection\Compiler\%sPass', \ucfirst(KodeCmsKodeExtension::EXT[$extension]), \ucfirst($extension));
            if (class_exists($className)) {
                $class = new $className();
                if ($class instanceof CompilerPassInterface) {
                    $container->addCompilerPass($class);
                }
            }
            if ($extension === Definable::CORE) {
                $dir = \sprintf('%s/../../kode-bundle-%s/src/%s/Entity', __DIR__, $extension, \ucfirst(Definable::CORE));
            } else {
                $dir = \sprintf('%s/../../kode-bundle-%s/src/Entity', __DIR__, $extension);
            }
            if (\is_dir($dir)) {
                $namespace = \sprintf('KodeCms\KodeBundle\%s\Entity', \ucfirst($extension));
                $container->addCompilerPass(DoctrineOrmMappingsPass::createAnnotationMappingDriver([$namespace], [$dir]));
            }
        }
    }
}
