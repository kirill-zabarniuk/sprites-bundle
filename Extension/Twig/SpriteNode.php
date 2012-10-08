<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

/**
 * Description of SpriteNode
 */
class SpriteNode extends \Twig_Node
{
    /**
     * Конструктор
     *
     * @param array   $attributes An array of attributes (should not be nodes)
     * @param integer $lineno     The line number
     * @param string  $tag        The tag name associated with the Node
     */
    public function __construct($attributes, $lineno, $tag = null)
    {
        parent::__construct(array(), $attributes, $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('echo strtr("<span class=\"'.$this->getAttribute('class').'\"></span>\\n", array())')
            ->raw(";\n");
    }
}
