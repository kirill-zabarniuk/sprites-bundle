<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader;
use Fernando\Bundle\SpritesBundle\Templating\CssTemplates;

/**
 * Description of SpriteTokenParser
 */
class SpriteTokenParser extends \Twig_TokenParser
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
        $this->templates  = $templates;
        $this->rootDir    = $rootDir;
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

    private function getCssClasses($src)
    {
        $filepath = $this->getWebDir() . DIRECTORY_SEPARATOR . $src;
        $info = $this->getImageInfoLoader()->load($filepath);

        $spriteId = $info->getTagsStr();
        $imageId  = $info->getHash();
        $size     = $info->getWidth() . 'x' . $info->getHeight();

        return sprintf(
            '%s %s %s %s',
            $this->getTemplates()->getCssClass(),
            $this->getTemplates()->getSpriteClass($spriteId),
            $this->getTemplates()->getImageClass($imageId),
            $this->getTemplates()->getSizeClass($size)
        );
    }

    /**
     * Вызвается каждый раз, когда парсер встречает sprite тэг
     * 
     * @param \Twig_Token $token
     * 
     * @return \Fernando\Bundle\SpritesBundle\Extension\Twig\SpriteNode
     */
    public function parse(\Twig_Token $token)
    {
        $src  = '';

        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        while (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test(\Twig_Token::STRING_TYPE)) {
                // 'image/ru.png'
                $src = $stream->next()->getValue();
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $attributes = array(
            'class' => $this->getCssClasses($src),
            'src'   => $src,
        );

        return new SpriteNode($attributes, $lineno, $this->getTag());
    }

    /**
     * Тэг, который парсим
     * 
     * @return string
     */
    public function getTag()
    {
        return 'sprite';
    }
}
