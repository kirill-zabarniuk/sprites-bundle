<?php

namespace Fernando\Bundle\SpritesBundle\Sprite\Image;

use Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader;

/**
 * Группировка сходных изображений в спрайты
 */
class InfoGroups
{
    private $infoLoader = null;
    private $groups = array();

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\ImageInfoLoader $infoLoader Сервис получения информации об изображениях
     */
    public function __construct(ImageInfoLoader $infoLoader)
    {
        $this->infoLoader = $infoLoader;
    }

    /**
     * Сервис получения информации об изображениях
     *
     * @return Image\ImageInfoLoader
     */
    private function getImageInfoLoader()
    {
        return $this->infoLoader;
    }

    /**
     * Добавление информации об изображении
     * 
     * @param string $path Путь к файлу
     * @param array  $tags Список доп. тэгов
     */
    public function add($path, $tags = array())
    {
        $info = $this->getImageInfoLoader()->getImageInfo($path, $tags);

        $groupId = $info->getTagsStr();
        if (!array_key_exists($groupId, $this->groups)) {
            $this->groups[$groupId] = new InfoGroup();
        }

        $this->groups[$groupId]->add($info);
    }

    /**
     * Получение сгруппированной информации об изображениях
     *
     * @return InfoGroup
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
