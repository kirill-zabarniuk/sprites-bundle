<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Fernando\Bundle\SpritesBundle\Cache\PhpConfigCache;

/**
 * Загрузка информации об изображении
 */
class ImageInfoLoader
{
    const CONFIG_CACHE_FILE = 'imagesInfo.cache.php';

    private $configCache = null;
    private $imagesInfo = null;

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Cache\PhpConfigCache $configCache
     */
    public function __construct(PhpConfigCache $configCache)
    {
        $this->configCache = $configCache;
    }

    private function getConfigCache()
    {
        return $this->configCache;
    }

    /**
     * Сохранение инф-ции об изображениях в кэше
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader
     */
    private function write()
    {
        $this->getConfigCache()->write(ImageInfoLoader::CONFIG_CACHE_FILE, $this->imagesInfo, true);

        return $this;
    }

    /**
     * Загрузка информации об изображенииях из кэша
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader
     */
    private function load()
    {
        $cc = $this->getConfigCache();

        if ($cc->has(ImageInfoLoader::CONFIG_CACHE_FILE)) {
            $this->imagesInfo = $cc->load(ImageInfoLoader::CONFIG_CACHE_FILE);
        }

        return $this;
    }

    /**
     * Получение объекта, хранящего информацию об изображении
     * 
     * @param string $filePath Путь к файлу
     * @param array  $tags     Список тэгов
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\Image\CachedImageInfo
     */
    public function getImageInfo($filePath, $tags = array())
    {
        if ($this->imagesInfo === null) {
            $this->load();
        }

        $cachedImageInfo = new CachedImageInfo($filePath, $tags);

        if (!isset($this->imagesInfo[$filePath])) {
            $imageInfo = new ImageInfo($filePath, $tags);
            $cachedImageInfo->setImageInfoObject($imageInfo);

            $this->imagesInfo[$filePath] = $imageInfo->toArray();
            $this->write();
        }

        $cachedImageInfo->fromArray($this->imagesInfo[$filePath]);

        return $cachedImageInfo;
    }
}
