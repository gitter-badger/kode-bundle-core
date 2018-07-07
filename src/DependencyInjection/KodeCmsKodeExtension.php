<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use Exception;
use KodeCms\KodeBundle\DependencyInjection\Component\Configurable;
use KodeCms\KodeBundle\DependencyInjection\Component\Definable;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KodeCmsKodeExtension extends Extension
{
    private const FILES = [
        'parameters.yaml',
        'services.yaml',
    ];

    public function getAlias(): string
    {
        return \KODE;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        foreach ($this->loadConfig($configs) as $key => $extension) {
            $this->loadExtension($container, $key, $extension);
            $this->checkComponent($key, $container);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param $key
     * @param array $extension
     *
     * @throws Exception
     */
    private function loadExtension(ContainerBuilder $container, $key, array $extension): void
    {
        foreach ($extension as $variable => $value) {
            if ($value === \reset($extension)) {
                $loader = new YamlFileLoader($container, new FileLocator(\sprintf('%s/../%s/Resources/config', __DIR__, \ucfirst($key))));
                foreach (self::FILES as $file) {
                    $this->getLocation($location, $key, $file);
                    if (\file_exists($location)) {
                        $loader->load($location);
                    }
                }
            }
            $container->setParameter(\sprintf('%s.%s.%s', $this->getAlias(), $key, $variable), $value);
        }
    }

    private function getLocation(&$location, $key, $file): void
    {
        if (Definable::EXT[$key] === Definable::CORE) {
            $location = \sprintf('%s/../../../kode-bundle-%s/src/%s/Resources/config/%s/%s', __DIR__, Definable::EXT[$key], \ucfirst(Definable::EXT[$key]), $key, $file);
        } else {
            $location = \sprintf('%s/../../../kode-bundle-%s/src/Resources/config/%s/%s', __DIR__, Definable::EXT[$key], $key, $file);
        }
    }

    private function unsetExtension(array &$extensions = []): void
    {
        foreach ($extensions as $key => $extension) {
            if (empty($extension['enabled'])) {
                unset($extensions[$key]);
            }
        }
    }

    private function getExtensions(array $configs): array
    {
        $extensions = [];
        $patterns = [
            \sprintf('/Unrecognized options "(.*?)" under "%s"/', $this->getAlias()),
            \sprintf('/Unrecognized option "(.*?)" under "%s"/', $this->getAlias()),
        ];
        try {
            $configuration = new Check($this->getAlias());
            /** @var $config array[] */
            $this->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            foreach ($patterns as $pattern) {
                if (\preg_match($pattern, $e->getMessage(), $matches) === 1) {
                    $extensions = \explode(',', $matches[1]);
                    foreach ($extensions as &$extension) {
                        $extension = \trim($extension);
                    }
                    unset($extension);
                    break;
                }
            }
        }

        return $extensions;
    }

    /**
     * @param array $configs
     *
     * @return array[]
     * @throws InvalidConfigurationException
     */
    private function loadConfig(array $configs): array
    {
        $extensions = $this->parseExtensions($configs);
        $configuration = new Configuration($this->getAlias(), $extensions);
        /** @var $config array[] */
        $config = $this->processConfiguration($configuration, $configs);

        if (!empty($config)) {
            $this->unsetExtension($config);
        }

        return $config ?? [];
    }

    private function parseExtensions(array $configs): array
    {
        $extensions = [];
        $defined = $this->getExtensions($configs);
        foreach ($defined as $def) {
            if (\is_dir(\sprintf('%s/../../../kode-bundle-%s', __DIR__, Definable::EXT[$def]))) {
                $extensions[] = $def;
            }
        }
        if ($extensions !== $defined) {
            $diff = \array_diff($defined, $extensions);
            throw new InvalidConfigurationException(\sprintf('Invalid extension%s: %s', \count($diff) > 1 ? 's' : '', \implode(', ', $diff)));
        }

        if (!isset($this->extensions[Definable::CORE])) {
            $extensions[] = Definable::CORE;
        }

        return $extensions;
    }

    private function checkComponent($key, ContainerBuilder $container): void
    {
        $className = \sprintf('KodeCms\KodeBundle\%s\DependencyInjection\%sConfiguration', \ucfirst(Definable::EXT[$key]), \ucfirst($key));
        if (\class_exists($className)) {
            $class = new $className($this->getAlias());
            if ($class instanceof Configurable) {
                $class->configure($container);
            }
        }
    }
}
