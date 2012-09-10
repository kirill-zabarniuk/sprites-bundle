<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Css;

use Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup;

/**
 * Description of Css
 */
class Css
{
    private $cssClass = 'sprite';
    private $headerTemplate   = "span.%class% {display: inline-block; background-repeat: no-repeat; display: -moz-inline-stack; zoom: 1; *display: inline;}\r\n";
    private $spriteTemplate   = "\r\n.g%group% {background-image: url(%url%);}\r\n";
    private $positionTemplate = ".i%image% {background-position: -%left%px -%top%px;}\r\n";
    private $sizeTemplate     = ".d%size% {width: %width%px; height: %height%px;}\r\n";

    private $dimensions = array();
    private $contents   = array();

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
     * Добавление информации о спрайте и изображениях
     *
     * @param string                                                $spriteId  Идентификатор спрайта
     * @param string                                                $filepath  Путь к спрайту
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup $infoGroup Объект InfoGroup с информацией об изображениях из спрайта
     */
    public function add($spriteId, $filepath, InfoGroup $infoGroup)
    {
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
     * Запись стилей в файл
     * 
     * @param string $filename
     */
    public function dump($filename)
    {
        $content = strtr($this->headerTemplate, array(
            '%class%' => $this->cssClass,
        ));
        $content .= implode('', $this->contents);
        foreach ($this->dimensions as $size => $dimension) {
            $content .= strtr($this->sizeTemplate, array(
                '%size%'   => $size,
                '%width%'  => $dimension['w'],
                '%height%' => $dimension['h'],
            ));
        }
        file_put_contents($filename, $content);
    }
}
