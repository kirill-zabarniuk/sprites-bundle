<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader;
use Fernando\Bundle\SpritesBundle\Templating\CssTemplates;

/**
 * Description of SpriteExtension
 */
class SpriteExtension extends \Twig_Extension
{
    /**
     * @var ImageInfoLoader
     */
    private $infoLoader = null;

    /**
     * @var CssTemplates
     */
    private $templates  = null;

    private $rootDir    = '';

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
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array(
            new SpriteTokenParser($this->infoLoader, $this->templates, $this->rootDir),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'sprite_extension';
    }
}
