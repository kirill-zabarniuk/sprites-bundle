<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;
use Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula;

/**
 * Loads asset formulae from Twig templates.
 */
class TwigFormulaLoader implements FormulaLoaderInterface
{
    private $twig    = null;
    private $formula = null;

    /**
     * Конструктор
     * 
     * @param \Twig_Environment                                                 $twig    Twig environment
     * @param \Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula $formula Объект, хранящий единую формулу для спрайтов
     */
    public function __construct(\Twig_Environment $twig, SpriteSingleFormula $formula)
    {
        $this->twig    = $twig;
        $this->formula = $formula;
    }

    /**
     * Loads formulae from a resource.
     *
     * Formulae should be loaded the same regardless of the current debug
     * mode. Debug considerations should happen downstream.
     *
     * @param ResourceInterface $resource A resource
     *
     * @return array An array of formulae
     */
    public function load(ResourceInterface $resource)
    {
        try {
            $tokens = $this->twig->tokenize($resource->getContent(), (string) $resource);
            $nodes  = $this->twig->parse($tokens);
        } catch (\Exception $e) {
            return array();
        }

        $this->loadNode($nodes);

        return $this->formula->getFormula();
    }

    /**
     * Loads assets from the supplied node.
     *
     * @param \Twig_Node $node
     *
     * @return array An array of asset formulae indexed by name
     */
    private function loadNode(\Twig_Node $node)
    {
        if ($node instanceof SpriteNode) {
            $this->formula->addInput($node->getAttribute('src'));
        } elseif ($node instanceof \Twig_Node_Expression_Function && $node->getAttribute('name') === 'sprite') {
            $args = $node->getNode('arguments');
            $firstArg = reset($args);
            $c = $firstArg[0];
            if ($c instanceof \Twig_Node_Expression_Constant) {
                $this->formula->addInput($c->getAttribute('value'));
            }
        }

        foreach ($node as $child) {
            if ($child instanceof \Twig_Node) {
                $this->loadNode($child);
            }
        }
    }
}
