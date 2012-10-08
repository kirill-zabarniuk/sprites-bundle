<?php

namespace Fernando\Bundle\SpritesBundle\Extension\Twig;

use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * Loads asset formulae from Twig templates.
 */
class TwigFormulaLoader implements FormulaLoaderInterface
{
    private $twig;
    private $inputs = array();

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function load(ResourceInterface $resource)
    {
        $formulae = array();
        $this->inputs = array();

        try {
            $tokens = $this->twig->tokenize($resource->getContent(), (string) $resource);
            $nodes  = $this->twig->parse($tokens);
        } catch (\Exception $e) {
            return array();
        }

        $this->loadNode($nodes);

        if (count($this->inputs)) {
            $formulae = array('twig_sprite' => array(
                $this->inputs,
                array('sprite'),
                array(),
            ));
        }

        return $formulae;
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
            $this->inputs[] = $node->getAttribute('src');
        }

        foreach ($node as $child) {
            if ($child instanceof \Twig_Node) {
                $this->loadNode($child);
            }
        }
    }
}
