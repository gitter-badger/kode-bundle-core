<?php

namespace KodeCms\KodeBundle;

use KodeCms\KodeBundle\DependencyInjection\KodeCmsKodeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KodeCmsKodeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            return new KodeCmsKodeExtension();
        }

        return $this->extension;
    }
}
