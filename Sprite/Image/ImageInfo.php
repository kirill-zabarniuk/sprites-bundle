<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Fernando\Bundle\SpritesBundle\Utils\Utils;

/**
 * Информация об изображении
 */
class ImageInfo extends AbstractImageInfo implements ImageInfoInterface
{
    /**
     * Конструктор
     * 
     * @param string $filePath Абсолютный путь к изображению
     * @param array  $tags     Массив тэгов
     */
    public function __construct($filePath, $tags = array())
    {
        $this->filePath = $filePath;
        $this->tags = $tags;

        $this->imagick = new \Imagick($filePath);
        $imageType = $this->imagick->getImageType();

        $this->alphaChannel = $this->imagick->getImageAlphaChannel();
        // Картинка анимирована?
        $this->numberImages = $this->imagick->getNumberImages();

        if ($this->numberImages > 1) {
            $this->delay = $this->imagick->getImageDelay(); // TODO: разный delay для разных кадров
            $this->tags[] = AbstractImageInfo::TAG_DELAY . $this->delay;

            $this->imagick->setIteratorIndex(0);
            $geometry = $this->imagick->getImage()->getImageGeometry();
        } else {
            $geometry = $this->imagick->getImageGeometry();
        }

        $this->height = $geometry['height'];
        $this->width = $geometry['width'];

        $this->tags[] = AbstractImageInfo::TAG_TYPE . $imageType;
        $this->tags[] = AbstractImageInfo::TAG_NUMBER_IMAGES . $this->numberImages;
    }
}
