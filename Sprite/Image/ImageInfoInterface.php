<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

/**
 * Информация об изображении
 */
interface ImageInfoInterface
{
    /**
     * Абсолютный путь к файлу
     *
     * @return string
     */
    public function getFilePath();

    /**
     * Объект imagick
     *
     * @return \Imagick
     */
    public function getImagick();

    /**
     * Ширина изображения
     *
     * @return int
     */
    public function getWidth();

    /**
     * Высота изображения
     *
     * @return int
     */
    public function getHeight();

    /**
     * Значение ImageAlphaChannel (прозрачность)
     *
     * @return int
     */
    public function getAlphaChannel();

    /**
     * Количество изображений ( > 1 для анимированных изображений)
     *
     * @return int
     */
    public function getNumberImages();

    /**
     * Массив тэгов
     *
     * @return array
     */
    public function getTags();

    /**
     * Хэш от набора тэгов, изображения с одинаковым хэшем попадают в один спрайт
     *
     * @return string
     */
    public function getTagsStr();

    /**
     * Хэш изображения
     *
     * @return type
     */
    public function getHash();
}
