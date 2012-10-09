<?php

namespace Fernando\Bundle\SpritesBundle\Factory\Loader;

/**
 * Formula, к-рая содержит src всех изображений
 */
class SpriteSingleFormula
{
    private $inputs  = array();
    private $filters = array();
    private $options = array();

    /**
     * Конструктор
     * 
     * @param array $inputs  Inputs
     * @param array $filters Filters
     * @param array $options Options
     */
    public function __construct($inputs = array(), $filters = array(), $options = array())
    {
        $this->inputs  = $inputs;
        $this->filters = $filters;
        $this->options = $options;
    }

    /**
     * Добавление input-а
     * 
     * @param array $input Input
     *
     * @return \Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula
     */
    public function addInput($input)
    {
        $this->inputs[] = $input;

        return $this;
    }

    /**
     * Присоединить формулу
     * 
     * @param array $formula
     * 
     * @return \Fernando\Bundle\SpritesBundle\Factory\Loader\SpriteSingleFormula
     */
    public function mergeFormula($formula = array())
    {
        $this->inputs  = array_merge($this->inputs, $formula[0]);
        $this->filters = array_merge($this->filters, $formula[1]);
        $this->options = array_merge($this->options, $formula[2]);

        return $this;
    }

    /**
     * Вывести формулу
     * 
     * @return array
     */
    public function getFormula()
    {
        return array('sprite' => array(
            array_unique($this->inputs),
            array_unique($this->filters),
            $this->options,
        ));
    }
}
