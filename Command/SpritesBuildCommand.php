<?php

namespace Fernando\Bundle\SpritesBundle\Command;

use Symfony\Component\Console\Command\Command;
//use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

/**
 * Description of SpritesBuildCommand
 */
class SpritesBuildCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('fernando:sprites:build')
            ->setDescription('Build sprites')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input  input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     *
     * @return
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();

        $rootDir = $kernel->getRootDir();
        $webDir = realpath(join(DIRECTORY_SEPARATOR, array(
            $rootDir,
            '..',
            'web',
        )));

//        // TRYING ASSETIC FILTERS
//        $sprites = new FileAsset($webDir . '/js/jquery-1.7.2.js');
//        // $sprites = new GlobAsset($webDir . '/bundles/fernandonews/img/*.png');
//
//        $filter = null;
//        // $filter = new \Assetic\Filter\Yui\JsCompressorFilter($rootDir . '/Resources/java/yuicompressor.jar');
//
//        $result = $sprites->dump($filter);
//        var_dump($result, $webDir);

        $infoCollection = new \Fernando\Bundle\SpritesBundle\Image\InfoCollection();

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->name('/\.(gif|png|jpe?g)$/')->in($webDir . strtr('/bundles/fernandonews/img', '/', DIRECTORY_SEPARATOR));
        foreach ($finder as $file) {
            $info = new \Fernando\Bundle\SpritesBundle\Image\Info($file->getRealpath());
            $infoCollection->add($info);
        }

//        $packer = new \Fernando\Bundle\SpritesBundle\Image\Sprite\PackerGuillotine();
        $packer = $container->get('fernando.sprites.packer');

//        foreach ($infoCollection->getCollections() as $group => $infoCollection) {
        foreach ($infoCollection->getDimensions() as $tagStr => $dimensions) {
            $positionMap = $packer->pack($dimensions);
            var_dump($positionMap);
        }
        die;
    }
}
