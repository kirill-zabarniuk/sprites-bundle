<?php

namespace Fernando\Bundle\SpritesBundle\Sprite;

use Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup;

/**
 * Description of BuilderBase
 */
class SpriteBuilder
{
    private $imagick = null;
    private $jpgEnabled = true;
    private $compressionQuality = 80;

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
     * Шаблон для спрайта
     *
     * @param int $width  Ширина
     * @param int $height Высота
     * 
     * @return \Imagick
     */
    protected function getImagickTemplate($width, $height)
    {
        $imagick = new \Imagick();
        $imagick->newImage($width, $height, "#OOOOOO");
        $imagick->setImageOpacity(0);

        return $imagick;
    }

    /**
     * Построение неанимированного спрайта
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup $infoGroup    Информация об изображениях
     * @param int                                                   $width        Ширина
     * @param int                                                   $height       Высота
     * @param int                                                   $alphaChannel Альфа-канал
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\SpriteBuilder
     */
    protected function buildStaticSprite(InfoGroup $infoGroup, $width, $height, $alphaChannel)
    {
        $this->imagick = $this->getImagickTemplate($width, $height);

        $positions = $infoGroup->getPositions();

        foreach ($infoGroup->getInfo() as $imageId => $info) {
            /* @var $info \Fernando\Bundle\SpritesBundle\Image\Info */
            $position = $positions[$imageId];

            $this->imagick->compositeImage(
                $info->getImagick(), $info->getImagick()->getImageCompose(), $position['l'], $position['t']
            );
        }

        if (!$alphaChannel && $this->jpgEnabled) {
            $this->imagick->setImageFormat('jpg');
            $this->imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $this->imagick->setImageCompressionQuality($this->compressionQuality);
        } else {
            $this->imagick->setImageFormat('png');
        }

        return $this;
    }

    protected function buildAnimatedSprite($infoGroup, $width, $height)
    {
        $layers = array();
        $delays = array();
        $positions = $infoGroup->getPositions();

        foreach ($infoGroup->getInfo() as $imageId => $info) {
            /* @var $info \Fernando\Bundle\SpritesBundle\Sprite\Image\Info */
            $position = $positions[$imageId];

            $layerId = 0;
            $img = $info->getImagick()->coalesceImages();
            do {
                if (!array_key_exists($layerId, $layers)) {
                    $layers[$layerId] = $this->getImagickTemplate($width, $height);
                    $delays[$layerId] = $img->getImageDelay();
                }

                $layers[$layerId]->compositeImage($img->getImage(), $img->getImageCompose(), $position['l'], $position['t']);
                ++$layerId;
            } while ($img->nextImage());
            // $img = $img->deconstructImages();
        }

        $this->imagick = new \Imagick();
        foreach ($layers as $layerId => $layer) {
            $this->imagick->addImage($layer);
            $this->imagick->setImageDelay($delays[$layerId]);
        }
        $this->imagick->setImageFormat('gif');

        return $this;
    }

    /**
     * Построение спрайта
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\Image\InfoGroup $infoGroup
     * 
     * @return \Fernando\Bundle\SpritesBundle\Sprite\SpriteBuilder
     */
    public function build(InfoGroup $infoGroup)
    {
        $isAnimated = false;
        $alphaChannel = 0;
        $spriteWidth  = 0;
        $spriteHeight = 0;

        $positions = $infoGroup->getPositions();

        foreach ($infoGroup->getInfo() as $imageId => $info) {
            /* @var $info \Fernando\Bundle\SpritesBundle\Sprite\Image\Info */
            $isAnimated = $isAnimated || ($info->getNumberImages() > 1);
            $alphaChannel = $alphaChannel || $info->getAlphaChannel();

            $position = $positions[$imageId];
            if ($position['l'] + $info->getWidth() > $spriteWidth) {
                $spriteWidth = $position['l'] + $info->getWidth();
            }

            if ($position['t'] + $info->getHeight() > $spriteHeight) {
                $spriteHeight = $position['t'] + $info->getHeight();
            }
        }

        return $isAnimated
            ? $this->buildAnimatedSprite($infoGroup, $spriteWidth, $spriteHeight)
            : $this->buildStaticSprite($infoGroup, $spriteWidth, $spriteHeight, $alphaChannel);
    }

    /**
     * Сохранение изображения
     * 
     * @param string $filepath
     *
     * @return
     */
    public function save($filepath)
    {
        if ($this->getImagick()->getImageFormat() != 'gif') {
            $this->getImagick()->writeImage($filepath);
        } else {
            $this->getImagick()->writeImages($filepath, true);
        }
    }
}
