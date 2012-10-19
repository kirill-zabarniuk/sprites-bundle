<?php

namespace Fernando\Bundle\SpritesBundle\Templating;

use Symfony\Component\Templating\Helper\Helper;
use Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader;
use Fernando\Bundle\SpritesBundle\Templating\CssTemplates;

/**
 * Description of SpriteHelper
 */
class SpriteHelper extends Helper
{
    private $templates;
    private $rootDir;
    private $infoLoader = null;

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader $infoLoader Сервис получения информации об изображениях
     * @param \Fernando\Bundle\SpritesBundle\Templating\CssTemplates      $templates  Сервис работы с шаблонами
     * @param string                                                      $rootDir    Application root directory
     */
    public function __construct(ImageInfoLoader $infoLoader, CssTemplates $templates, $rootDir)
    {
        $this->infoLoader = $infoLoader;
        $this->templates = $templates;
        $this->rootDir = $rootDir;
    }

    /**
     * Сервис получения информации об изображениях
     * 
     * @return ImageInfoLoader
     */
    private function getImageInfoLoader()
    {
        return $this->infoLoader;
    }

    /**
     * Сервис css шаблонов
     *
     * @return CssTemplates
     */
    private function getTemplates()
    {
        return $this->templates;
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

    /**
     * Хэлпер для вывода изображения
     * 
     * @param string $relativePath Путь к изображению относительно web root
     *
     * @return string
     */
    public function sprite($relativePath)
    {
        $filepath = $this->getWebDir() . DIRECTORY_SEPARATOR . $relativePath;
        $info = $this->getImageInfoLoader()->getImageInfo($filepath);

        $spriteId = $info->getTagsStr();
        $imageId  = $info->getHash();
        $size     = $info->getWidth() . 'x' . $info->getHeight();

        return sprintf(
            '<span class="%s %s %s %s"></span>',
            $this->getTemplates()->getCssClass(),
            $this->getTemplates()->getSpriteClass($spriteId),
            $this->getTemplates()->getImageClass($imageId),
            $this->getTemplates()->getSizeClass($size)
        );
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'fernando';
    }
}
