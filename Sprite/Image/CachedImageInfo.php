<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Assetic\Cache\ConfigCache;

/**
 * Description of CachedImageInfo
 */
class CachedImageInfo implements ImageInfoInterface
{
    const KEY_WIDTH         = 'width';
    const KEY_HEIGHT        = 'height';
    const KEY_ALPHA_CHANNEL = 'alpha_channel';
    const KEY_NUMBER_IMAGES = 'number_images';
    const KEY_TAGS_STR      = 'tags_str';
    const KEY_HASH          = 'hash';

    private $filePath;
    private $tags;
    private $configCache = null;
    private $loaded     = false;
    private $info       = array();
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
     * Установка объекта для работы с кэшем
     * 
     * @param \Assetic\Cache\ConfigCache $configCache
     */
    public function setConfigCache(ConfigCache $configCache)
    {
        $this->configCache = $configCache;
    }

    /**
     * Получение объекта для работы с кэшем
     * 
     * @return ConfigCache
     */
    public function getConfigCache()
    {
        return $this->configCache;
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

    private function toArray(ImageInfoInterface $infoObject)
    {
        return array(
            CachedImageInfo::KEY_WIDTH         => $infoObject->getWidth(),
            CachedImageInfo::KEY_HEIGHT        => $infoObject->getHeight(),
            CachedImageInfo::KEY_ALPHA_CHANNEL => $infoObject->getAlphaChannel(),
            CachedImageInfo::KEY_NUMBER_IMAGES => $infoObject->getNumberImages(),
            CachedImageInfo::KEY_TAGS_STR      => $infoObject->getTagsStr(),
            CachedImageInfo::KEY_HASH          => $infoObject->getHash(),
        );
    }

    /**
     * Загрузка данных из кэша (создание нового объекта InfoObject и сохранение в кэш)
     */
    private function load()
    {
        if ($this->getConfigCache() === null) {
            throw new \Exception('ConfigCache is not set.');
        }

        $id = $this->getFilePath();
        if (!$this->getConfigCache()->has($id)) {
            $this->info = $this->toArray($this->getImageInfoObject());

            $this->getConfigCache()->set($id, $this->info);
        } else {
            $this->info = $this->getConfigCache()->get($id);
        }

        $this->loaded = true;
    }

    private function getValue($key)
    {
        if (!$this->loaded) {
            $this->load();
        }

        return $this->info[$key];
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
     * Т.к. требуется только при построении спрайта (т.е. при деплое),
     * то не сохраняется в кэш
     *
     * @return \Imagick
     */
    public function getImagick()
    {
        return $this->getImageInfoObject()->getImagick();
    }

    /**
     * Ширина изображения
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->getValue(CachedImageInfo::KEY_WIDTH);
    }

    /**
     * Высота изображения
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->getValue(CachedImageInfo::KEY_HEIGHT);
    }

    /**
     * Значение ImageAlphaChannel (прозрачность)
     *
     * @return int
     */
    public function getAlphaChannel()
    {
        return $this->getValue(CachedImageInfo::KEY_ALPHA_CHANNEL);
    }

    /**
     * Количество изображений ( > 1 для анимированных изображений)
     *
     * @return int
     */
    public function getNumberImages()
    {
        return $this->getValue(CachedImageInfo::KEY_NUMBER_IMAGES);
    }

    /**
     * Хэш от набора тэгов, изображения с одинаковым хэшем попадают в один спрайт
     *
     * @return string
     */
    public function getTagsStr()
    {
        return $this->getValue(CachedImageInfo::KEY_TAGS_STR);
    }

    /**
     * Хэш изображения
     *
     * @return type
     */
    public function getHash()
    {
        return $this->getValue(CachedImageInfo::KEY_HASH);
    }
}
