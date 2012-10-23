<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Description of SpriteTokenParser
 */
class SpriteTokenParser extends \Twig_TokenParser
{
    /**
     * @var \Fernando\Bundle\SpritesBundle\Templating\SpriteHelper
     */
    private $helper = null;

    /**
     * Конструктор
     *
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader $infoLoader Сервис получения информации об изображениях
     * @param \Fernando\Bundle\SpritesBundle\Templating\CssTemplates      $templates  Сервис работы с шаблонами
     * @param string                                                      $rootDir    Application root directory
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    private function getSpriteHelper()
    {
        return $this->helper;
    }

    private function getCssClasses($src)
    {
        return $this->getSpriteHelper()->getCssClasses($src);
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
