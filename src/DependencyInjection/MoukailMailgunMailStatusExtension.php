<?php

namespace Moukail\MailgunMailStatusBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MoukailMailgunMailStatusExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.xml');

        //$loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__) .'/Resources/config'));
        //$loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'moukail_mailgun_mail_status';
    }
}
