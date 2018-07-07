<?php

namespace KodeCms\KodeBundle\Core\Twig;

trait TwigTrait
{
    private $shortFunctions;

    public function makeArray(array $input, $type = 'filter'): array
    {
        $output = [];
        $class = \sprintf('\\Twig_Simple%s', \ucfirst($type));
        $this->makeInput($input, $input);

        foreach ($input as $call => $function) {
            if (\is_array($function)) {
                $options = $function[2] ?? [];
                unset($function[2]);
                $output[] = new $class($call, $function, $options);
            } else {
                $output[] = new $class($call, [
                    $this,
                    $function,
                ]);
            }
        }

        return $output;
    }

    private function makeInput(array $input, &$output): void
    {
        $output = [];
        foreach ($input as $call => $function) {
            if ($this->shortFunctions) {
                $output[$call] = $function;
            }
            $output[\sprintf('kode_%s', $call)] = $function;
        }
    }
}
