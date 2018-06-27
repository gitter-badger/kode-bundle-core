<?php

namespace KodeCms\KodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Exception;

if (!\defined('KODE')) {
    \define('KODE', 'kode_cms_kode');
}

class KodeCmsKodeExtension extends Extension
{
    private const FILES = [
        'parameters.yaml',
        'services.yaml',
    ];

    public const EXT = [
        'translatable' => 'translatable',
        'captcha' => 'captcha',
        'guzzle' => 'guzzle',
        'lexik' => 'translatable',
        'mobile' => 'extension',
        'oauth' => 'oauth',
        'openid' => 'oauth',
        'openidconnect' => 'oauth',
        'pagination' => 'position',
        'position' => 'position',
        'sitemap' => 'sitemap',
        'core' => 'core',
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
        foreach ($this->loadConfig($configs, $container) as $key => $extension) {
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
        }

        \dump($container);exit;
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
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return array[]
     * @throws Exception
     */
    private function loadConfig(array $configs, ContainerBuilder $container): array
    {
        $extensions = [];
        $defined = $this->getExtensions($configs);
        foreach ($defined as $def) {
            if (is_dir(\sprintf('%s/../../../kode-bundle-%s', __DIR__, self::EXT[$def]))) {
                $extensions[] = $def;
            }
        }
        if ($extensions !== $defined) {
            $diff = \array_diff($defined, $extensions);
            throw new InvalidConfigurationException(\sprintf('Invalid extension%s: %s', count($diff) > 1 ? 's' : '', implode(', ', $diff)));
        }

        if (!isset($extensions['core'])) {
            $extensions[] = 'core';
        }

        $configuration = new Configuration($this->getAlias(), $extensions);
        /** @var $config array[] */
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(\sprintf('%s/../Resources/config', __DIR__)));
        foreach (self::FILES as $file) {
            if (\file_exists(\sprintf('%s/../Resources/config/%s', __DIR__, $file))) {
                $loader->load($file);
            }
        }

        $container->setParameter('kode_alias', $this->getAlias());
        if (!empty($config)) {
            $this->unsetExtension($config);
        }

        return $config ?? [];
    }
}
