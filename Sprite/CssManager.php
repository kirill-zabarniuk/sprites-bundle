<?php

namespace Fernando\Bundle\SpritesBundle\Sprite;

use Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup;

/**
 * Description of Css
 */
class CssManager
{
    private $filename = 'sprite.css';
    private $cssDir   = 'css';

    private $cssClass = 'sprite';
    private $headerTemplate   = "span.%class% {display: inline-block; background-repeat: no-repeat; display: -moz-inline-stack; zoom: 1; *display: inline;}\r\n";
    private $spriteTemplate   = "\r\n.g%group% {background-image: url(%url%);}\r\n";
    private $positionTemplate = ".i%image% {background-position: -%left%px -%top%px;}\r\n";
    private $sizeTemplate     = ".d%size% {width: %width%px; height: %height%px;}\r\n";

    private $spriteIds  = array();
    private $dimensions = array();
    private $contents   = array();

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
     * Имя файла со стилями для спрайтов
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Установка имени css-класса, который используется для работы со спрайтами
     * 
     * @param string $class
     *
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Css\Css
     */
    public function setCssClass($class)
    {
        $this->cssClass = $class;

        return $this;
    }

    /**
     * Получение имени css-класса, который используется для работы со спрайтами
     * 
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * Установка шаблона с общими стилями
     *
     * Доступны placeholder-ы: %class%
     * 
     * @param string $template
     *
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Css\Css
     */
    public function setHeaderTemplate($template)
    {
        $this->headerTemplate = $template;

        return $this;
    }

    /**
     * Установка шаблона, описывающего стили спрайта
     *
     * Доступны placeholder-ы: %group%, %url%
     *
     * @param string $template
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Css\Css
     */
    public function setSpriteTemplate($template)
    {
        $this->spriteTemplate = $template;

        return $this;
    }

    /**
     * Установка шаблона, описывающего стили изображений
     * 
     * Доступны placeholder-ы: %image%, %left%, %top%
     *
     * @param string $template
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Css\Css
     */
    public function setImageTemplate($template)
    {
        $this->positionTemplate = $template;

        return $this;
    }

    /**
     * Установка шаблона, описывающего размеры изображений
     * 
     * Доступны placeholder-ы: %size%, %width%, %height%
     *
     * @param string $template
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Css\Css
     */
    public function setSizeTemplate($template)
    {
        $this->sizeTemplate = $template;

        return $this;
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

        $this->contents[$spriteId] = strtr($this->spriteTemplate, array(
            '%group%' => $spriteId,
            '%url%'   => $filepath,
        ));
        foreach ($infoGroup->getPositions() as $imageId => $position) {
            $this->contents[$spriteId] .= strtr($this->positionTemplate, array(
                '%image%' => $imageId,
                '%left%'  => $position['l'],
                '%top%'   => $position['t'],
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
        return strtr($this->headerTemplate, array(
            '%class%' => $this->cssClass,
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
            $content .= strtr($this->sizeTemplate, array(
                '%size%'   => $size,
                '%width%'  => $dimension['w'],
                '%height%' => $dimension['h'],
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
