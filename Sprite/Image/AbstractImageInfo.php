<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Fernando\Bundle\SpritesBundle\Utils\Utils;

/**
 * Информация об изображении
 */
class AbstractImageInfo implements ImageInfoInterface
{
    const TAG_TYPE          = 't';
    const TAG_NUMBER_IMAGES = 'n';
    const TAG_DELAY         = 'd';
    const TAGS_SEPARATOR    = ':';

    const KEY_FILEPATH      = 'filepath';
    const KEY_WIDTH         = 'width';
    const KEY_HEIGHT        = 'height';
    const KEY_ALPHA_CHANNEL = 'alpha_channel';
    const KEY_NUMBER_IMAGES = 'number_images';
    const KEY_TAGS          = 'tags';
    const KEY_TAGS_STR      = 'tags_str';
    const KEY_HASH          = 'hash';

    /** @var \Imagick */
    protected $imagick = null;

    protected $filePath = '';
    protected $tags = array();

    protected $width = 0;
    protected $height = 0;
    protected $alphaChannel;
    protected $numberImages;
    protected $delay = null;

    protected $fileHash = null;
    protected $tagsStr = '';

    /**
     * Абсолютный путь к файлу
     *
     * @param string $filePath
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Абсолютный путь к файлу
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
     * @param \Imagick $imagick
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setImagick(\Imagick $imagick)
    {
        $this->imagick = $imagick;

        return $this;
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
     * @param int $width
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
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
     * @param int $height
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
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
     * @param int $alphaChannel
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setAlphaChannel($alphaChannel)
    {
        $this->alphaChannel = $alphaChannel;

        return $this;
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
     * @param int $nbImages
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setNumberImages($nbImages)
    {
        $this->numberImages = $nbImages;

        return $this;
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
     * @param array $tags
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
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
     * @param string $tagsStr
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setTagsStr($tagsStr)
    {
        $this->tagsStr = $tagsStr;

        return $this;
    }

    /**
     * Хэш от набора тэгов, изображения с одинаковым хэшем попадают в один спрайт
     *
     * @return string
     */
    public function getTagsStr()
    {
        if ($this->tagsStr !== '') {
            return $this->tagsStr;
        }

        $tags = $this->getTags();
        sort($tags);

        return dechex(Utils::crc16(
            implode(AbstractImageInfo::TAGS_SEPARATOR, $tags)
        ));
    }

    /**
     * Хэш изображения
     *
     * @param string $fileHash
     *
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function setHash($fileHash)
    {
        $this->fileHash = $fileHash;

        return $this;
    }

    /**
     * Хэш изображения
     * 
     * @return type
     */
    public function getHash()
    {
        if ($this->fileHash === null) {
            $this->fileHash = hash_file('crc32', $this->getFilePath());
        }

        return $this->fileHash;
    }

    /**
     * Представление объекта в виде массива
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            AbstractImageInfo::KEY_FILEPATH      => $this->getFilePath(),
            AbstractImageInfo::KEY_WIDTH         => $this->getWidth(),
            AbstractImageInfo::KEY_HEIGHT        => $this->getHeight(),
            AbstractImageInfo::KEY_ALPHA_CHANNEL => $this->getAlphaChannel(),
            AbstractImageInfo::KEY_NUMBER_IMAGES => $this->getNumberImages(),
            AbstractImageInfo::KEY_TAGS          => $this->getTags(),
            AbstractImageInfo::KEY_TAGS_STR      => $this->getTagsStr(),
            AbstractImageInfo::KEY_HASH          => $this->getHash(),
        );
    }

    /**
     * Установка св-в объекта на основе значений массива
     * 
     * @param array $values
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\AbstractImageInfo
     */
    public function fromArray($values)
    {
        isset($values[AbstractImageInfo::KEY_FILEPATH]) && $this->setFilePath($values[AbstractImageInfo::KEY_FILEPATH]);
        isset($values[AbstractImageInfo::KEY_WIDTH]) && $this->setWidth($values[AbstractImageInfo::KEY_WIDTH]);
        isset($values[AbstractImageInfo::KEY_HEIGHT]) && $this->setHeight($values[AbstractImageInfo::KEY_HEIGHT]);
        isset($values[AbstractImageInfo::KEY_ALPHA_CHANNEL]) && $this->setAlphaChannel($values[AbstractImageInfo::KEY_ALPHA_CHANNEL]);
        isset($values[AbstractImageInfo::KEY_NUMBER_IMAGES]) && $this->setNumberImages($values[AbstractImageInfo::KEY_NUMBER_IMAGES]);
        isset($values[AbstractImageInfo::KEY_TAGS]) && $this->setTags($values[AbstractImageInfo::KEY_TAGS]);
        isset($values[AbstractImageInfo::KEY_TAGS_STR]) && $this->setTagsStr($values[AbstractImageInfo::KEY_TAGS_STR]);
        isset($values[AbstractImageInfo::KEY_HASH]) && $this->setHash($values[AbstractImageInfo::KEY_HASH]);

        return $this;
    }
}
