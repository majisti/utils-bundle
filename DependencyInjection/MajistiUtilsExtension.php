<?php

namespace Majisti\UtilsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\Config\Definition\Processor;

class MajistiUtilsExtension extends Extension
{
    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, 
            new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('majisti_utils.test.cache_sqlite_db', 
            $config['test']['cache_sqlite_db']);
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
