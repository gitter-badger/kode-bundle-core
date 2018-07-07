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
    public const SEARCH = 'search';

    public const EXT = [
        Definable::TRANSLATABLE => Definable::TRANSLATABLE,
        Definable::CAPTCHA => Definable::CAPTCHA,
        Definable::GUZZLE => Definable::GUZZLE,
        Definable::LEXIK => Definable::TRANSLATABLE,
        Definable::SCRIPT => Definable::CORE,
        Definable::OAUTH => Definable::OAUTH,
        Definable::OPENID => Definable::OAUTH,
        Definable::OPENIDCONNECT => Definable::OAUTH,
        Definable::PAGINATION => Definable::POSITION,
        Definable::POSITION => Definable::POSITION,
        Definable::SITEMAP => Definable::SITEMAP,
        Definable::CORE => Definable::CORE,
        Definable::REACT => Definable::REACT,
        Definable::SEARCH => Definable::SEARCH,
    ];
    public const FIXED = [
        Definable::TRANSLATABLE,
        Definable::CAPTCHA,
        Definable::GUZZLE,
        Definable::CORE,
        Definable::OAUTH,
        Definable::POSITION,
        Definable::SITEMAP,
        Definable::REACT,
        Definable::SEARCH,
    ];

    public function getExtensionDefinition($extension): ArrayNodeDefinition;
}
