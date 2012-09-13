<?php

namespace Fernando\Bundle\SpritesBundle\Factory\Loader;

use Assetic\Factory\AssetFactory;
use Assetic\Factory\Loader\BasePhpFormulaLoader;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * Парсит php шаблоны и строит формулы, по которым затем создаются asset-ы
 */
class FormulaLoader extends BasePhpFormulaLoader
{
    private $inputs = array();

    /**
     * Возвращает формулу (используется фиксированное имя)
     * 
     * @param ResourceInterface $resource A resource
     *
     * @return array An array of formulae
     */
    public function load(ResourceInterface $resource)
    {
        $formula = parent::load($resource);

        $filters = array('sprite');
        $options = array();
        foreach ($formula as $formulae) {
            $this->inputs = array_merge($this->inputs, $formulae[0]);
            $options = array_merge($options, $formulae[2]);
        }

        return array('sprite' => array(
            $this->inputs,
            $filters,
            $options,
        ));
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
