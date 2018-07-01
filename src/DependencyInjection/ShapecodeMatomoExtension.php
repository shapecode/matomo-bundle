<?php

namespace Shapecode\Bundle\MatomoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeMatomoExtension
 *
 * @package Shapecode\Bundle\MatomoBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodeMatomoExtension extends ConfigurableExtension
{

    /**
     * @inheritdoc
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        foreach ($config as $name => $configParameterKey) {
            $container->setParameter('shapecode_matomo.' . $name, $configParameterKey);
        }

        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new Loader\YamlFileLoader($container, $locator);
        $loader->load('services.yml');
    }

}
