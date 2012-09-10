<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

/**
 * Информация об изображениях из одного спрайта
 */
class InfoGroup
{
    /** @var array Массив объектов класса Info */
    private $info = array();

    /** @var array Массив с размерами изображений */
    private $dimensions = array();

    /** @var array Массив с координатами изображений на спрайте */
    private $positions = array();

    /**
     * Добавление информации об изображении
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\Info $info Информация об изображении
     *
     * @return
     */
    public function add(Info $info)
    {
        $this->info[$info->getHash()] = $info;
        $this->dimensions[$info->getHash()] = array(
            'h' => $info->getHeight(),
            'w' => $info->getWidth(),
        );
    }

    /**
     * Получение инфморации об изображении
     * 
     * @return Info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Получение массива с размерами изображений
     *
     * @return array
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Установка значений массива с координатами изображений на спрайте
     *
     * @param array $positions Массив с координатами изображений на спрайте
     *
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup
     */
    public function setPositions($positions = array())
    {
        $this->positions = $positions;

        return $this;
    }

    /**
     * Получение массива с координатами изображений на спрайте
     * 
     * @return array
     */
    public function getPositions()
    {
        if (!isset($this->positions)) {
            $this->positions = $this->packer->getPositions(
                $this->getDimensions()
            );
        }

        return $this->positions;
    }
}
