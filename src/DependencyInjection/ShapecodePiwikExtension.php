<?php

namespace Shapecode\Bundle\PiwikBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodePiwikExtension
 *
 * @package Shapecode\Bundle\PiwikBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodePiwikExtension extends ConfigurableExtension
{

    /**
     * @inheritdoc
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        foreach ($config as $name => $configParameterKey) {
            $container->setParameter('shapecode_piwik.' . $name, $configParameterKey);
        }
    }

}
