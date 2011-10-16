<?php

namespace Majisti\UtilsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class MajistiUtilsExtension extends Extension
{
    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, 
            new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function getAlias()
    {
        return 'majisti_utils';
    }
}
