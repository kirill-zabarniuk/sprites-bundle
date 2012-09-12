<?php

namespace Fernando\Bundle\SpritesBundle\Factory\Loader;

use Assetic\Factory\Loader\BasePhpFormulaLoader;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * Description of FormulaLoader
 */
class FormulaLoader extends BasePhpFormulaLoader
{
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
