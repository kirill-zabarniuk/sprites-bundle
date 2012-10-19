<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

/**
 * Description of CachedImageInfo
 */
class CachedImageInfo extends AbstractImageInfo implements ImageInfoInterface
{

    private $infoObject = null;

    /**
     * Конструктор
     * 
     * @param string $filePath Абсолютный путь к изображению
     * @param array  $tags     Тэги
     */
    public function __construct($filePath, $tags = array())
    {
        $this->filePath = $filePath;
        $this->tags = $tags;
    }

    /**
     * Устанавливает объект ImageInfo для работы с ним
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoInterface $imageInfo
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\CachedImageInfo
     */
    public function setImageInfoObject(ImageInfoInterface $imageInfo)
    {
        $this->infoObject = $imageInfo;

        return $this;
    }

    /**
     * Возвращает реальный объект ImageInfo и создает его если нужно
     *
     * @return ImageInfoInterface
     */
    private function getImageInfoObject()
    {
        if ($this->infoObject === null) {
            $this->infoObject = new ImageInfo($this->filePath, $this->tags);
        }

        return $this->infoObject;
    }

    /**
     * Объект imagick
     *
     * Т.к. требуется только при построении спрайта (т.е. при деплое),
     * то не сохраняется в кэш
     *
     * @return \Imagick
     */
    public function getImagick()
    {
        return $this->getImageInfoObject()->getImagick();
    }
}
