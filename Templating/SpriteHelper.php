<?php

namespace Fernando\Bundle\SpritesBundle\Templating;

use Symfony\Component\Templating\Helper\Helper;
use Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoInterface;
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

    private function expandAttributes($attributes = array())
    {
        $result = array();
        foreach ($attributes as $attribute => $value) {
            $result[] = is_int($attribute)
                ? $value
                : sprintf('%s="%s"', $attribute, $value);
        }

        return implode(' ', $result);
    }

    /**
     * Код тэга img для вывода изображения
     * 
     * @param string $relativePath Путь к изображению относительно web root
     * @param array  $attributes   Доп. атрибуты тэга
     * @param array  $options      Опции
     *
     * @return string
     */
    private function getImg($relativePath, $attributes, $options)
    {
        // TODO: опция для вывода абсолютного пути
        $attr['src'] = '/' . $relativePath;

        return sprintf('<img %s />', $this->expandAttributes(array_merge($attr, $attributes)));
    }

    /**
     * Код тэга span для вывода изображения в виде спрайта
     *
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoInterface $info       Информация об изображении
     * @param array                                                          $attributes Доп. атрибуты тэга
     * @param array                                                          $options    Опции
     *
     * @return string
     */
    private function getSpan(ImageInfoInterface $info, $attributes, $options)
    {
        $spriteId = $info->getTagsStr();
        $imageId  = $info->getHash();
        $size     = $info->getWidth() . 'x' . $info->getHeight();

        // установка атрибута class (предполагается что он передан с ключом 'class')
        $class = isset($attributes['class']) ? ' ' . $attributes['class'] : '';
        unset($attributes['class']);
        $attr['class'] = sprintf(
            '%s %s %s %s%s',
            $this->getTemplates()->getCssClass(),
            $this->getTemplates()->getSpriteClass($spriteId),
            $this->getTemplates()->getImageClass($imageId),
            $this->getTemplates()->getSizeClass($size),
            $class
        );

        return sprintf('<span %s></span>', $this->expandAttributes(array_merge($attr, $attributes)));
    }

    /**
     * Хэлпер для вывода изображения
     * 
     * @param string $relativePath Путь к изображению относительно web root
     * @param array  $attributes   Доп. атрибуты тэга
     * @param array  $options      Опции
     *
     * @return string
     */
    public function sprite($relativePath, $attributes = array(), $options = array())
    {
        $tags = isset($options['tags']) ? $options['tags'] : array();

        $path = $this->getWebDir() . DIRECTORY_SEPARATOR . $relativePath;
        $info = $this->getImageInfoLoader()->getImageInfo($path, $tags, true);

        return ($info === null)
            ? $this->getImg($relativePath, $attributes, $options)
            : $this->getSpan($info, $attributes, $options);
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
