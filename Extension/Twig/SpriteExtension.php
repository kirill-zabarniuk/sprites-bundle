<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Description of SpriteExtension
 */
class SpriteExtension extends \Twig_Extension
{
    /**
     * @var \Fernando\Bundle\SpritesBundle\Templating\SpriteHelper
     */
    private $helper = null;

    /**
     * Конструктор
     *
     * @param \Symfony\Component\Templating\Helper\Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    private function getSpriteHelper()
    {
        return $this->helper;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'sprite' => new \Twig_Function_Method($this, 'sprite', array(
                'is_safe' => array('html'),
            )),
        );
    }

    /**
     * Генерация html-кода для показа изображения (img или span)
     *
     * @param string $relativePath Путь к изображению относительно web root
     * @param array  $attributes   Атрибуты
     *
     * @return string
     */
    public function sprite($relativePath, $attributes = array())
    {
        return $this->getSpriteHelper()->sprite($relativePath, $attributes);
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array(
            new SpriteTokenParser($this->helper),
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
