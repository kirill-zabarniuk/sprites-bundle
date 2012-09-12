<?php

namespace Fernando\Bundle\SpritesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Fernando\Bundle\SpritesBundle\DependencyInjection\DirectoryResourceDefinition;

/**
 * Adds services tagged as assets to the asset manager.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 */
class AssetManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('assetic.asset_manager')) {
            return;
        }

        $am = $container->getDefinition('assetic.asset_manager');

        // Set DirectoryResourceDefinition
        // TODO: раскомментировать $engines
        // $engines = $container->getParameter('templating.engines');
        $engines = array('php');
        // bundle and kernel resources
        $bundles = $container->getParameter('kernel.bundles');
        $alias = $container->getParameter('fernando.formula_loader.alias');
        $asseticBundles = $container->getParameterBag()->resolveValue($container->getParameter('assetic.bundles'));
        foreach ($asseticBundles as $bundleName) {
            $rc = new \ReflectionClass($bundles[$bundleName]);
            foreach ($engines as $engine) {
                $this->setBundleDirectoryResources($container, $engine, $alias, dirname($rc->getFileName()), $bundleName);
            }
        }

        foreach ($engines as $engine) {
            $this->setAppDirectoryResources($container, $engine, $alias);
        }

        // add resources
        foreach ($container->findTaggedServiceIds('assetic.formula_resource') as $id => $attributes) {
            foreach ($attributes as $attr) {
                if (isset($attr['loader'])) {
                    $am->addMethodCall('addResource', array(new Reference($id), $attr['loader']));
                }
            }
        }
    }

    protected function setBundleDirectoryResources(ContainerBuilder $container, $engine, $loader, $bundleDirName, $bundleName)
    {
        $container->setDefinition(
            'fernando.'.$engine.'_directory_resource.'.$bundleName,
            new DirectoryResourceDefinition($bundleName, $engine, $loader, array(
                $container->getParameter('kernel.root_dir').'/Resources/'.$bundleName.'/views',
                $bundleDirName.'/Resources/views',
            ))
        );
    }

    protected function setAppDirectoryResources(ContainerBuilder $container, $engine, $loader)
    {
        $container->setDefinition(
            'fernando.'.$engine.'_directory_resource.kernel',
            new DirectoryResourceDefinition('', $engine, $loader, array($container->getParameter('kernel.root_dir').'/Resources/views'))
        );
    }
}
