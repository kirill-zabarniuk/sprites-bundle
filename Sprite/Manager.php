<?php

namespace Fernando\Bundle\SpritesBundle\Sprite;

use Fernando\Bundle\SpritesBundle\Packer\PackerInterface;

/**
 * Description of Manager
 */
class Manager
{
    private $rootDir;
    private $packer;

    public function __construct(PackerInterface $packer, $rootDir)
    {
        $this->packer  = $packer;
        $this->rootDir = $rootDir;
    }

    /**
     * Возвращает сервис Packer
     * 
     * @return  \Fernando\Bundle\SpritesBundle\Packer\PackerInterface
     */
    private function getPacker()
    {
        return $this->packer;
    }

    private function getRootDir()
    {
        return $this->rootDir;
    }

    private function getWebDir()
    {
        return realpath(join(DIRECTORY_SEPARATOR, array(
            $this->getRootDir(), '..', 'web',
        )));
    }

    private function getImgDir()
    {
        return realpath(join(DIRECTORY_SEPARATOR, array(
            $this->getWebDir(), 'img',
        )));
    }

    private function getCssDir()
    {
        return realpath(join(DIRECTORY_SEPARATOR, array(
            $this->getWebDir(), 'css',
        )));
    }

    public function process($files)
    {
        $infoGroups = new \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroups();

        foreach ($files as $file) {
            $infoGroups->add($file);
        }

        $builder = new \Fernando\Bundle\SpritesBundle\Sprite\BuilderBase();
        $css = new \Fernando\Bundle\SpritesBundle\Sprite\Css\Css();
        foreach ($infoGroups->getGroups() as $groupId => $infoGroup) {
            /* @var $infoGroup \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup */
            $positions = $this->getPacker()->getPositions($infoGroup->getDimensions());
            $infoGroup->setPositions($positions);

            $sprite = $builder->build($infoGroup);
            $fileName = md5($sprite->getImagick()->getImageBlob()) . '.' . $sprite->getImagick()->getImageFormat();
            $sprite->save($this->getImgDir() . DIRECTORY_SEPARATOR . $fileName);

            $css->add($groupId, 'img' . DIRECTORY_SEPARATOR . $fileName, $infoGroup);
        }
        $css->dump($this->getCssDir() . DIRECTORY_SEPARATOR . 'sprite.css');
    }

    public function processAll()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder
            ->files()
            ->name('/\.(gif|png|jpe?g)$/')
            ->in($this->getWebDir() . strtr('/bundles/fernandonews/img', '/', DIRECTORY_SEPARATOR))
        ;

        $files = array();
        foreach ($finder as $file) {
            $files[] = $file->getRealpath();
        }

        $this->process($files);
    }
}
