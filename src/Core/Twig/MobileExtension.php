<?php

namespace KodeCms\KodeBundle\Core\Twig;

use Mobile_Detect;
use Twig_Extension;

class MobileExtension extends Twig_Extension
{
    use TwigTrait;

    public $detector;

    public function __construct($shortFunctions)
    {
        $this->shortFunctions = $shortFunctions;
        $this->detector = new Mobile_Detect();
    }

    public function getFunctions(): array
    {
        $input = [
            'is_mobile' => [
                $this->detector,
                'isMobile',
            ],
            'is_tablet' => [
                $this->detector,
                'isTablet',
            ],
        ];

        return $this->makeArray($input, 'function');
    }
}
