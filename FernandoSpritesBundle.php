<?php

namespace Fernando\Bundle\SpritesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Fernando\Bundle\SpritesBundle\DependencyInjection\Compiler\AssetManagerPass;

class FernandoSpritesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AssetManagerPass());
    }
}
