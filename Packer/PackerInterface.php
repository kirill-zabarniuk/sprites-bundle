<?php

namespace Fernando\Bundle\SpritesBundle\Packer;

/**
 * Интерфейс для классов, отвечающих за расположение изображений на спрайте
 */
interface PackerInterface
{
    /**
     * Вычисление координат изображений на спрайте
     *
     * @param array $dimensions Массив с размерами изображений
     * @param array $options    Опции
     *
     * @return array
     */
    public function getPositions($dimensions, $options = array());
}
