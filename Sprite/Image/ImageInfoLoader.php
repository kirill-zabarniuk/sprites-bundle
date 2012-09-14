<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Assetic\Cache\ConfigCache;

/**
 * Загрузка информация об изображении
 */
class ImageInfoLoader
{
    private $configCache = null;

    /**
     * Конструктор
     * 
     * @param \Assetic\Cache\ConfigCache $configCache
     */
    public function __construct(ConfigCache $configCache)
    {
        $this->configCache = $configCache;
    }

    private function getConfigCache()
    {
        return $this->configCache;
    }

    /**
     * Загрузка информации об изображении
     * 
     * @param string $filePath Абсолютный путь к изображению
     * @param array  $tags     Массив тэгов
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoInterface
     */
    public function load($filePath, $tags = array())
    {
        $imageInfo = new CachedImageInfo($filePath, $tags);
        $imageInfo->setConfigCache($this->getConfigCache());

        return $imageInfo;
    }
}
