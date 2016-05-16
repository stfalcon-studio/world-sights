<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 *  This is the class that loads and manages AppBundle configuration
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form_types.yml');
        $loader->load('listeners.yml');
        $loader->load('param_converters.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'app';
    }
}
