<?php

namespace Fernando\Bundle\SpritesBundle\Templating;

/**
 * Description of CssTemplates
 */
class CssTemplates
{
    private $cssClass = 'sprite';
    private $spriteClass = 'g%s';
    private $imageClass  = 'i%s';
    private $sizeClass   = 'd%s';

    private $headerTemplate   = "span.%class% {display: inline-block; background-repeat: no-repeat; display: -moz-inline-stack; zoom: 1; *display: inline;}\r\n";
    private $spriteTemplate   = ".%sprite_class% {background-image: url(%url%);}\r\n";
    private $positionTemplate = ".%image_class% {background-position: -%left%px -%top%px;}\r\n";
    private $sizeTemplate     = ".%size_class% {width: %width%px; height: %height%px;}\r\n";

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

    public function getSpriteClass($spriteId = '')
    {
        return sprintf($this->spriteClass, $spriteId);
    }

    public function getImageClass($imageId = '')
    {
        return sprintf($this->imageClass, $imageId);
    }

    public function getSizeClass($size = '')
    {
        return sprintf($this->sizeClass, $size);
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

    public function getHeaderTemplate($arguments = array())
    {
        return strtr($this->headerTemplate, $arguments);
    }

    /**
     * Установка шаблона, описывающего стили спрайта
     *
     * Доступны placeholder-ы: %sprite_class%, %url%
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
    
    public function getSpriteTemplate($arguments = array())
    {
        return strtr($this->spriteTemplate, $arguments);
    }

    /**
     * Установка шаблона, описывающего стили изображений
     *
     * Доступны placeholder-ы: %image_class%, %left%, %top%
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

    public function getImageTemplate($arguments = array())
    {
        return strtr($this->positionTemplate, $arguments);
    }

    /**
     * Установка шаблона, описывающего размеры изображений
     *
     * Доступны placeholder-ы: %size_class%, %width%, %height%
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

    public function getSizeTemplate($arguments = array())
    {
        return strtr($this->sizeTemplate, $arguments);
    }

}
