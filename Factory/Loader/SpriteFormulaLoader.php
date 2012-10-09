<?php

namespace Fernando\Bundle\SpritesBundle\Factory\Loader;

use Assetic\Factory\Loader\BasePhpFormulaLoader;
use Assetic\Factory\Resource\ResourceInterface;
use Assetic\Factory\AssetFactory;
use Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula;

/**
 * Парсит php шаблоны и строит формулы, по которым затем создаются asset-ы
 */
class SpriteFormulaLoader extends BasePhpFormulaLoader
{
    private $formula = null;

    /**
     * Конструктор
     * 
     * @param \Assetic\Factory\AssetFactory                                     $factory Asset factory
     * @param \Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula $formula Объект, хранящий единую формулу для спрайтов
     */
    public function __construct(AssetFactory $factory, SpriteSingleFormula $formula)
    {
        $this->formula = $formula;
        parent::__construct($factory);
    }

    /**
     * Возвращает формулу (используется фиксированное имя)
     * 
     * @param ResourceInterface $resource A resource
     *
     * @return array An array of formulae
     */
    public function load(ResourceInterface $resource)
    {
        $formulas = parent::load($resource);

        foreach ($formulas as $formula) {
            $this->formula->mergeFormula($formula);
        }

        return $this->formula->getFormula();
    }

    protected function registerPrototypes()
    {
        return array(
            '$view[\'fernando\']->sprite(*)'      => array('output' => 'images/*', 'single' => true),
            '$view["fernando"]->sprite(*)'        => array('output' => 'images/*', 'single' => true),
            '$view->get(\'fernando\')->sprite(*)' => array('output' => 'images/*', 'single' => true),
            '$view->get("fernando")->sprite(*)'   => array('output' => 'images/*', 'single' => true),
        );
    }

    protected function registerSetupCode()
    {
        return <<<'EOF'
class Helper
{
    public function sprite()
    {
        global $_call;
        $_call = func_get_args();
    }
}

class View extends ArrayObject
{
    public function __construct(Helper $helper)
    {
        parent::__construct(array('fernando' => $helper));
    }

    public function get()
    {
        return $this['fernando'];
    }
}

$view = new View(new Helper());
EOF;
    }
}
