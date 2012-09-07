<?php

namespace Fernando\Bundle\SpritesBundle\Image;

use Symfony\Component\Finder\SplFileInfo;
use Fernando\Bundle\SpritesBundle\Utils\Utils;

/**
 * Description of Info
 */
class Info
{
    const TAG_TYPE = 't';
    const TAG_NUMBER_IMAGES = 'n';
    const TAG_DELAY = 'd';
    const TAGS_SEPARATOR = ':';

    private $filePath;
    private $tags;
    private $width;
    private $height;

    private $delay = null;

    public function __construct($filePath, $tags = array())
    {
        $this->filePath = $filePath;
        $this->tags = $tags;

        $imagick = new \Imagick($filePath);
        $imageType = $imagick->getImageType();

        // Картинка анимирована?
        $numberImages = $imagick->getNumberImages();

        if ($numberImages > 1) {
            $this->delay = $imagick->getImageDelay();
            $this->tags[] = Info::TAG_DELAY . $this->delay;

            $imagick->setIteratorIndex(0);
            $geometry = $imagick->getImage()->getImageGeometry();
        } else {
            $geometry = $imagick->getImageGeometry();
        }

        $this->height = $geometry['height'];
        $this->width = $geometry['width'];

        $this->tags[] = Info::TAG_TYPE . $imageType;
        $this->tags[] = Info::TAG_NUMBER_IMAGES . $numberImages;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
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
     * Уникальный id изображения
     * 
     * @return type
     */
    public function getHash()
    {
        return hash_file('crc32', $this->getFilePath());
    }
}
