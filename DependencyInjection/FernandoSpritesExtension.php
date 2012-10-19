<?php

namespace Fernando\Bundle\SpritesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FernandoSpritesExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('fernando.java', $config['java']);
        $container->setParameter('fernando.sprites.jar_yml', $config['jar_yml']);
        $container->setParameter('fernando.sprites.jar_packer', $config['jar_packer']);
        $container->setParameter('fernando.sprites.css_class', $config['css']['class']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('templating.xml');
        if ($config['assetic']['enabled']) {
            $container->setParameter('fernando.sprites.assetic_enabled', $config['css']['class']);
            $container->setParameter('fernando.sprites.assetic_filters', $config['assetic']['formula_filters']);
            $loader->load('assetic.xml');
        }
    }
}
