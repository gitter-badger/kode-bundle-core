<?php

namespace KodeCms\KodeBundle\DependencyInjection\Component;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

interface Definable
{
    public const CORE = 'core';
    public const TRANSLATABLE = 'translatable';
    public const CAPTCHA = 'captcha';
    public const GUZZLE = 'guzzle';
    public const LEXIK = 'lexik';
    public const SCRIPT = 'script';
    public const OAUTH = 'oauth';
    public const OPENID = 'openid';
    public const OPENIDCONNECT = 'openidconnect';
    public const POSITION = 'position';
    public const PAGINATION = 'pagination';
    public const SITEMAP = 'sitemap';
    public const REACT = 'react';

    public function getExtensionDefinition($extension): ArrayNodeDefinition;
}
