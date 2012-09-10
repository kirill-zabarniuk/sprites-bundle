<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Fernando\Bundle\SpritesBundle\Utils\Utils;

/**
 * Информация об изображении
 */
class Info
{
    const TAG_TYPE = 't';
    const TAG_NUMBER_IMAGES = 'n';
    const TAG_DELAY = 'd';
    const TAGS_SEPARATOR = ':';

    /** @var \Imagick */
    private $imagick;

    private $filePath;
    private $tags;

    private $width;
    private $height;
    private $alphaChannel;
    private $numberImages;
    private $delay = null;

    private $fileHash;

    /**
     * Конструктор
     * @param string $filePath Путь к изображению
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
            $this->tags[] = Info::TAG_DELAY . $this->delay;

            $this->imagick->setIteratorIndex(0);
            $geometry = $this->imagick->getImage()->getImageGeometry();
        } else {
            $geometry = $this->imagick->getImageGeometry();
        }

        $this->height = $geometry['height'];
        $this->width = $geometry['width'];

        $this->tags[] = Info::TAG_TYPE . $imageType;
        $this->tags[] = Info::TAG_NUMBER_IMAGES . $this->numberImages;
    }

    /**
     * Путь к файлу
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Объект imagick
     *
     * @return \Imagick
     */
    public function getImagick()
    {
        return $this->imagick;
    }

    /**
     * Ширина изображения
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Высота изображения
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Значение ImageAlphaChannel (прозрачность)
     * 
     * @return int
     */
    public function getAlphaChannel()
    {
        return $this->alphaChannel;
    }

    /**
     * Количество изображений ( > 1 для анимированных изображений)
     *
     * @return int
     */
    public function getNumberImages()
    {
        return $this->numberImages;
    }

    /**
     * Массив тэгов
     * 
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Хэш от набора тэгов, изображения с одинаковым хэшем попадают в один спрайт
     *
     * @return string
     */
    public function getTagsStr()
    {
        $tags = $this->getTags();
        sort($tags);

        return dechex(Utils::crc16(
            implode(Info::TAGS_SEPARATOR, $tags)
        ));
    }

    /**
     * Хэш изображения
     * 
     * @return type
     */
    public function getHash()
    {
        if (!isset($this->fileHash)) {
            $this->fileHash = hash_file('crc32', $this->getFilePath());
        }

        return $this->fileHash;
    }
}
