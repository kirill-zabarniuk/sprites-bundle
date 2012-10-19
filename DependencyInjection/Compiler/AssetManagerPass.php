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
        if (!$container->hasParameter('fernando.sprites.assetic_enabled')) {
            return;
        }
        if (!$container->hasDefinition('assetic.asset_manager')) {
            return;
        }

        $am = $container->getDefinition('assetic.asset_manager');

        // Set DirectoryResourceDefinition
        $engines = $container->getParameter('templating.engines');
        // bundle and kernel resources
        $bundles = $container->getParameter('kernel.bundles');
        $asseticBundles = $container->getParameterBag()->resolveValue($container->getParameter('assetic.bundles'));
        foreach ($asseticBundles as $bundleName) {
            $rc = new \ReflectionClass($bundles[$bundleName]);
            foreach ($engines as $engine) {
                $loader = $container->getParameter(
                    sprintf('fernando.formula_loader_%s.alias', $engine)
                );
                $this->setBundleDirectoryResources($container, $engine, $loader, dirname($rc->getFileName()), $bundleName);
            }
        }

        foreach ($engines as $engine) {
            $loader = $container->getParameter(
                sprintf('fernando.formula_loader_%s.alias', $engine)
            );
            $this->setAppDirectoryResources($container, $engine, $loader);
        }

        // add resources
        foreach ($container->findTaggedServiceIds('assetic.formula_resource') as $id => $attributes) {
            foreach ($attributes as $attr) {
                if (isset($attr['loader'])) {
                    $am->addMethodCall('addResource', array(new Reference($id), $attr['loader']));
                }
            }
        }

        // хак, для того чтобы спрайты обрабатывались в первую очередь и сгенерировался sprite.css
        $this->reorderMethodCalls($am);
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

    protected function reorderMethodCalls(\Symfony\Component\DependencyInjection\Definition $am)
    {
        $calls = $am->getMethodCalls();
        usort($calls, function($a, $b) {
            if ($a[0] === 'addResource' && $b[0] === 'addResource') {
                $aliases = array('php_sprite', 'twig_sprite');
                $aFirst = in_array($a[1][1], $aliases);
                $bFirst = in_array($b[1][1], $aliases);

                if ($aFirst && $bFirst) {
                    return 0;
                }

                return $aFirst ? -1 : 1;
            }

            if ($a[0] !== 'addResource' && $b[0] !== 'addResource') {
                return 0;
            }

            return $a[0] === 'addResource' ? 1 : -1;
        });
        $am->setMethodCalls($calls);
    }
}
