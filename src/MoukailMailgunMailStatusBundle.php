<?php

namespace Moukail\MailgunMailStatusBundle;

use Moukail\MailgunMailStatusBundle\DependencyInjection\MoukailMailgunMailStatusExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MoukailMailgunMailStatusBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new MoukailMailgunMailStatusExtension();
        }

        return $this->extension;
    }
}
