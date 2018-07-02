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

    public static $extensions = [];

    public const EXT = [
        Definable::TRANSLATABLE => Definable::TRANSLATABLE,
        Definable::CAPTCHA => Definable::CAPTCHA,
        Definable::GUZZLE => Definable::GUZZLE,
        Definable::LEXIK => Definable::TRANSLATABLE,
        Definable::MOBILE => Definable::CORE,
        Definable::OAUTH => Definable::OAUTH,
        Definable::OPENID => Definable::OAUTH,
        Definable::OPENIDCONNECT => Definable::OAUTH,
        Definable::PAGINATION => Definable::POSITION,
        Definable::POSITION => Definable::POSITION,
        Definable::SITEMAP => Definable::SITEMAP,
        Definable::CORE => Definable::CORE,
    ];

    public const FIXED = [
        Definable::TRANSLATABLE,
        Definable::CAPTCHA,
        Definable::GUZZLE,
        Definable::CORE,
        Definable::OAUTH,
        Definable::POSITION,
        Definable::SITEMAP,
    ];

    public function getAlias(): string
    {
        return KODE;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        foreach ($this->loadConfig($configs) as $key => $extension) {
            /** @var $extension array */
            foreach ($extension as $variable => $value) {
                if ($value === \reset($extension)) {
                    $loader = new YamlFileLoader($container, new FileLocator(\sprintf('%s/../%s/Resources/config', __DIR__, \ucfirst($key))));
                    foreach (self::FILES as $file) {
                        $location = \sprintf('%s/../../../kode-bundle-%s/src/Resources/config/%s/%s', __DIR__, self::EXT[$key], $key, $file);
                        if (\file_exists($location)) {
                            $loader->load($location);
                        }
                    }
                }
                $container->setParameter(\sprintf('%s.%s.%s', $this->getAlias(), $key, $variable), $value);
            }
            $this->checkComponent($key, $container);
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
                    $extensions = explode(',', $matches[1]);
                    foreach ($extensions as &$extension) {
                        $extension = trim($extension);
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
        $this->parseExtensions($configs);
        $configuration = new Configuration($this->getAlias(), self::$extensions);
        /** @var $config array[] */
        $config = $this->processConfiguration($configuration, $configs);

        if (!empty($config)) {
            $this->unsetExtension($config);
        }

        return $config ?? [];
    }

    private function parseExtensions(array $configs): void
    {
        $defined = $this->getExtensions($configs);
        foreach ($defined as $def) {
            if (is_dir(\sprintf('%s/../../../kode-bundle-%s', __DIR__, self::EXT[$def]))) {
                self::$extensions[] = $def;
            }
        }
        if (self::$extensions !== $defined) {
            $diff = \array_diff($defined, self::$extensions);
            throw new InvalidConfigurationException(\sprintf('Invalid extension%s: %s', \count($diff) > 1 ? 's' : '', implode(', ', $diff)));
        }

        if (!isset($this->extensions[Definable::CORE])) {
            self::$extensions[] = Definable::CORE;
        }
    }

    private function checkComponent($key, ContainerBuilder $container): void
    {
        $className = sprintf('KodeCms\KodeBundle\%s\DependencyInjection\%sConfiguration', \ucfirst(self::EXT[$key]), \ucfirst($key));
        if (class_exists($className)) {
            $class = new $className($this->getAlias());
            if ($class instanceof Configurable) {
                $class->configure($container);
            }
        }
    }
}
