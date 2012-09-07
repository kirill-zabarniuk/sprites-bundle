<?php

namespace Fernando\Bundle\SpritesBundle\Image;

/**
 * Работа с набрами объектов класса Info
 */
class InfoCollection
{
    /** @var array Наборы объектов класса Info, сгруппированные на основе тэгов */
    private $collections = array();

    /** @var type array Массив с размерами изображений */
    private $dimensions = array();

    public function add(Info $info)
    {
        $this->collections[$info->getTagsStr()][] = $info;
        $this->dimensions[$info->getTagsStr()][$info->getHash()] = array(
            'h' => $info->getHeight(),
            'w' => $info->getWidth(),
        );
    }

    public function getCollections()
    {
        return $this->collections;
    }

    public function getDimensions()
    {
        return $this->dimensions;
    }

//    public function getDimensionsByTag($tagStr)
//    {
//        return $this->dimensions[$tagStr];
//    }


    public function dump()
    {
        var_dump($this->collections);
    }
}
