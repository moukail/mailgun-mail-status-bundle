<?php

namespace Moukail\MailgunMailStatusBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Moukail\MailgunMailStatusBundle\DependencyInjection\MoukailMailgunMailStatusExtension;

class MoukailMailgunMailStatusBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new MoukailMailgunMailStatusExtension();
        }

        return $this->extension;
    }
}
