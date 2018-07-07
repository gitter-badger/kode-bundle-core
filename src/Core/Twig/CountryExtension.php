<?php

namespace KodeCms\KodeBundle\Core\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Twig_Extension;

class CountryExtension extends Twig_Extension
{
    use TwigTrait;

    private $locale;

    public function __construct(RequestStack $requestStack, $shortFunctions)
    {
        $this->shortFunctions = $shortFunctions;
        $this->locale = $requestStack->getCurrentRequest()->getDefaultLocale();
    }

    public function getFilters(): array
    {
        $input = [
            'country' => 'getCountryName',
        ];

        return $this->makeArray($input);
    }

    public function getCountryName($country, $locale = null)
    {
        return Intl::getRegionBundle()->getCountryName($country, $locale ?? $this->locale);
    }
}
