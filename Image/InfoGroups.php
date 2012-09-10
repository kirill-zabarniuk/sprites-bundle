<?php

namespace Fernando\Bundle\SpritesBundle\Image;

use Fernando\Bundle\SpritesBundle\Image\Info;
use Fernando\Bundle\SpritesBundle\Image\InfoGroup;

/**
 * Группировка сходных изображений в спрайты
 */
class InfoGroups
{
    private $groups = array();

    /**
     * Добавление информации об изображении
     * 
     * @param string $path Путь к файлу
     */
    public function add($path)
    {
        $info = new Info($path);

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
