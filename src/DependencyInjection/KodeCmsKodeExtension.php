<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

if (!\defined('KODE')) {
    \define('KODE', 'kode_cms_kode');
}

class KodeCmsKodeExtension extends Extension
{
    private const FILES = ['components.yaml', 'parameters.yaml', 'services.yaml',];

    public function getAlias()
    {
        return KODE;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->loadConfig($configs, $container);

        foreach ($config['extensions'] as $key => $extension) {
            /** @var $extension array */
            foreach ($extension as $variable => $value) {
                if ($value === reset($extension)) {
                    $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../'.ucfirst($key).'/Resources/config'));
                    foreach (self::FILES as $file) {
                        if (file_exists(__DIR__.'/../'.ucfirst($key).'/Resources/config/'.$file)) {
                            $loader->load($file);
                        }
                    }
                }
                $container->setParameter($this->getAlias().'.'.$key.'.'.$variable, $value);
            }
        }
    }

    private function unsetExtension(array &$extensions = [])
    {
        foreach ($extensions as $key => $extension) {
            if (empty($extension['enabled'])) {
                unset($extensions[$key]);
            }
        }
    }

    private function loadConfig(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($this->getAlias());
        /** @var $config array[] */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach (self::FILES as $file) {
            if (\file_exists(__DIR__.'/../Resources/config/'.$file)) {
                $loader->load($file);
            }
        }

        $container->setParameter('kode_alias', KODE);
        $this->unsetExtension($config['extensions']);

        return $config;
    }
}