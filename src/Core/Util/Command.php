<?php

namespace KodeCms\KodeBundle\Core\Util;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command
{
    public static function initialize(array $arguments, ContainerAwareCommand $command): bool
    {
        [
            $params,
            $input,
            $output,
        ] = $arguments;
        /** @var InputInterface $input */
        /** @var OutputInterface $output */

        foreach ($params as $param => $check) {
            $command->{$param} = $input->getOption($param);
            if (!$command->{$param}) {
                $output->writeln(\sprintf('Please provide --%s parameter!', $param));

                return false;
            }

            if (!$check($command->{$param})) {
                $output->writeln(\sprintf('Incorrect input in --%s parameter!', $param));

                return false;
            }
        }

        return true;
    }
}
