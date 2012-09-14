<?php

namespace Fernando\Bundle\SpritesBundle\Sprite;

use Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup;
use Fernando\Bundle\SpritesBundle\Templating\CssTemplates;

/**
 * Description of Css
 */
class CssManager
{
    private $templates;
    private $filename = 'sprite.css';
    private $cssDir   = 'css';

    private $spriteIds  = array();
    private $dimensions = array();
    private $contents   = array();

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Templating\CssTemplates $templates
     */
    public function __construct(CssTemplates $templates)
    {
        $this->templates = $templates;
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

    /**
     * Имя файла со стилями для спрайтов
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Установка каталога для сохранения css файлов
     * 
     * @param string $dir Путь относительно web root
     */
    public function setCssDir($dir)
    {
        $this->cssDir = $dir;
    }

    /**
     * Получение каталога для сохранения css файлов (относительно web root)
     * 
     * @return string
     */
    public function getCssDir()
    {
        return $this->cssDir;
    }

    /**
     * Id спрайтов
     * 
     * @return array
     */
    public function getSpriteIds()
    {
        return $this->spriteIds;
    }

    /**
     * Добавление информации о спрайте и изображениях
     *
     * @param string                                                $spriteId  Идентификатор спрайта
     * @param string                                                $filepath  Путь к спрайту
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup $infoGroup Объект InfoGroup с информацией об изображениях из спрайта
     */
    public function add($spriteId, $filepath, InfoGroup $infoGroup)
    {
        $this->spriteIds[] = $spriteId;

        foreach ($infoGroup->getDimensions() as $dimensions) {
            $key = $dimensions['w'] . 'x' . $dimensions['h'];
            $this->dimensions[$key] = $dimensions;
        }

        $this->contents[$spriteId] = $this->getTemplates()->getSpriteTemplate(array(
            '%sprite_class%' => $this->getTemplates()->getSpriteClass($spriteId),
            '%url%'          => '/' . $filepath,
        ));

        foreach ($infoGroup->getPositions() as $imageId => $position) {
            $this->contents[$spriteId] .= $this->getTemplates()->getImageTemplate(array(
                '%image_class%' => $this->getTemplates()->getImageClass($imageId),
                '%left%'        => $position['l'],
                '%top%'         => $position['t'],
            ));
        }
    }

    /**
     * Общий заголовок для всех стилей
     *
     * @return string
     */
    public function getHeaderCss()
    {
        return $this->getTemplates()->getHeaderTemplate(array(
            '%class%' => $this->getTemplates()->getCssClass(),
        ));
    }

    /**
     * Получение стилей для спрайта с указанным id
     * 
     * @param string $spriteId
     *
     * @return string
     * @throws \Exception
     */
    public function getContentCss($spriteId)
    {
        if (!array_key_exists($spriteId, $this->contents)) {
            throw new \Exception('Sprite id not found.');
        }

        return $this->contents[$spriteId];
    }

    /**
     * Стили, устанавливающие размеры элементов
     * 
     * @return string
     */
    public function getDimensionsCss()
    {
        $content = '';
        foreach ($this->dimensions as $size => $dimension) {
            $content .= $this->getTemplates()->getSizeTemplate(array(
                '%size_class%' => $this->getTemplates()->getSizeClass($size),
                '%width%'      => $dimension['w'],
                '%height%'     => $dimension['h'],
            ));
        }

        return $content;
    }

    /**
     * Получение всех стилей
     * 
     * @return string
     */
    public function getCss()
    {
        return $this->getHeaderCss()
            . implode('', $this->contents)
            . $this->getDimensionsCss();
    }

    /**
     * Запись стилей в файл
     * 
     * @param string $filename
     */
    public function dump($filename)
    {
        file_put_contents($filename, $this->getCss());
    }
}
