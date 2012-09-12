<?php

namespace Fernando\Bundle\SpritesBundle\Command;

use Symfony\Component\Console\Command\Command;
//use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
//use Assetic\Asset\FileAsset;
//use Assetic\Asset\GlobAsset;

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

//        // TRYING ASSETIC FILTERS
//        $sprites = new FileAsset($webDir . '/js/jquery-1.7.2.js');
//        // $sprites = new GlobAsset($webDir . '/bundles/fernandonews/img/*.png');
//
//        $filter = null;
//        // $filter = new \Assetic\Filter\Yui\JsCompressorFilter($rootDir . '/Resources/java/yuicompressor.jar');
//
//        $result = $sprites->dump($filter);
//        var_dump($result, $webDir);

//        // SPRITES MANAGER
//        $rootDir = $kernel->getRootDir();
//        $webDir = realpath(join(DIRECTORY_SEPARATOR, array(
//            $rootDir, '..', 'web',
//        )));
//        $imgDir = realpath(join(DIRECTORY_SEPARATOR, array(
//            $webDir, 'img',
//        )));
//        $cssDir = realpath(join(DIRECTORY_SEPARATOR, array(
//            $webDir, 'css',
//        )));
//
//        $infoGroups = new \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroups();
//
//        $finder = new \Symfony\Component\Finder\Finder();
//        $finder
//            ->files()
//            ->name('/\.(gif|png|jpe?g)$/')
//            ->in($webDir . strtr('/bundles/fernandonews/img', '/', DIRECTORY_SEPARATOR))
//        ;
//        foreach ($finder as $file) {
//            $infoGroups->add($file->getRealpath());
//        }
//
//        $packer = $container->get('fernando.sprites.packer');
//        /* @var $packer \Fernando\Bundle\SpritesBundle\Packer\PackerInterface */
//        $builder = new \Fernando\Bundle\SpritesBundle\Sprite\BuilderBase();
//        $css = new \Fernando\Bundle\SpritesBundle\Sprite\Css\Css();
//        foreach ($infoGroups->getGroups() as $groupId => $infoGroup) {
//            /* @var $infoGroup \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup */
//            $positions = $packer->getPositions($infoGroup->getDimensions());
//            $infoGroup->setPositions($positions);
//
//            $sprite = $builder->build($infoGroup);
//            $fileName = md5($sprite->getImagick()->getImageBlob()) . '.' . $sprite->getImagick()->getImageFormat();
//            $sprite->save($imgDir . DIRECTORY_SEPARATOR . $fileName);
//
//            $css->add($groupId, 'img' . DIRECTORY_SEPARATOR . $fileName, $infoGroup);
//        }
//        $css->dump($cssDir . DIRECTORY_SEPARATOR . 'sprite.css');

        $manager = $container->get('fernando.sprites.manager');
        /* @var $manager \Fernando\Bundle\SpritesBundle\Sprite\Manager */
        $manager->processAll();
    }
}
