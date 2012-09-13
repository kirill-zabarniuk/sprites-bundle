<?php

namespace Fernando\Bundle\SpritesBundle\Sprite;

use Fernando\Bundle\SpritesBundle\Packer\PackerInterface;
use Fernando\Bundle\SpritesBundle\Sprite\CssManager;

/**
 * Description of Manager
 */
class SpriteManager
{
    private $packer;
    private $cssManager;
    private $rootDir;
    private $files = array();

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Packer\PackerInterface $packer     Packer сервис
     * @param \Fernando\Bundle\SpritesBundle\Sprite\CssManager      $cssManager Css Manager
     * @param string                                                $rootDir    Application root directory
     */
    public function __construct(PackerInterface $packer, CssManager $cssManager, $rootDir)
    {
        $this->packer     = $packer;
        $this->cssManager = $cssManager;
        $this->rootDir    = $rootDir;
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

    /**
     * @return CssManager
     */
    public function getCssManager()
    {
        return $this->cssManager;
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

    /**
     * Сборка файлов в спрайт и подготовка информации для сохранения css файлов
     * 
     * @param array $files Список файлов
     */
    public function process($files)
    {
        $infoGroups = new \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroups();

        foreach ($files as $file) {
            $infoGroups->add($file);
        }

        $builder = new \Fernando\Bundle\SpritesBundle\Sprite\SpriteBuilder();
        $css = $this->getCssManager();
        foreach ($infoGroups->getGroups() as $groupId => $infoGroup) {
            /* @var $infoGroup \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup */
            $positions = $this->getPacker()->getPositions($infoGroup->getDimensions());
            $infoGroup->setPositions($positions);

            $sprite = $builder->build($infoGroup);
            $fileName = md5($sprite->getImagick()->getImageBlob()) . '.' . $sprite->getImagick()->getImageFormat();
            $sprite->save($this->getImgDir() . DIRECTORY_SEPARATOR . $fileName);

            $css->add($groupId, 'img' . DIRECTORY_SEPARATOR . $fileName, $infoGroup);
        }
//        $css->dump($this->getCssDir() . DIRECTORY_SEPARATOR . 'sprite.css');
    }

    /**
     * Запуск сборки спрайтов
     */
    public function processFiles()
    {
        $files = array_unique($this->files);

        if (count($files) > 1) {
            $this->process($files);
        }
    }

    /**
     * Добавление файла для его размещения в спрайте
     * 
     * @param string $file Абсолютный путь к файлу
     */
    public function addFile($file)
    {
        $this->files[] = $file;
    }

    /**
     * Сборка спрайта из всех файлов в указанной директории
     */
    public function processAll()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder
            ->files()
            ->name('/\.(gif|png|jpe?g)$/')
            ->in($this->getWebDir() . strtr('/bundles/fernandonews/img', '/', DIRECTORY_SEPARATOR));

        foreach ($finder as $file) {
            $this->addFile($file->getRealpath());
        }

        $this->processFiles();
    }
}