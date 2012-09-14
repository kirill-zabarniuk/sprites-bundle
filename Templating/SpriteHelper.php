<?php

namespace Fernando\Bundle\SpritesBundle\Templating;

use Symfony\Component\Templating\Helper\Helper;
use Fernando\Bundle\SpritesBundle\Templating\CssTemplates;

/**
 * Description of SpriteHelper
 */
class SpriteHelper extends Helper
{
    private $templates;
    private $rootDir;

    public function __construct(CssTemplates $templates, $rootDir)
    {
        $this->templates = $templates;
        $this->rootDir = $rootDir;
    }

    /**
     * Сервис css шаблонов
     *
     * @return CssTemplates
     */
    private function getTemplates()
    {
        return $this->templates;
    }

    private function getRootDir()
    {
        return $this->rootDir;
    }

    private function getWebDir()
    {
        return realpath(join(DIRECTORY_SEPARATOR, array(
            $this->getRootDir(), '..', 'web',
        )));
    }

    public function getName()
    {
        return 'fernando';
    }

    public function sprite($relativePath)
    {
        $filepath = $this->getWebDir() . DIRECTORY_SEPARATOR . $relativePath;
        $info = new \Fernando\Bundle\SpritesBundle\Sprite\Image\Info($filepath);

        $spriteId = $info->getTagsStr();
        $imageId  = $info->getHash();
        $size     = $info->getWidth() . 'x' . $info->getHeight();
//        echo "<pre>";
//        var_dump($info);

        return sprintf(
            '<span class="%s %s %s %s"></span>',
            $this->getTemplates()->getCssClass(),
            $this->getTemplates()->getSpriteClass($spriteId),
            $this->getTemplates()->getImageClass($imageId),
            $this->getTemplates()->getSizeClass($size)
        );
    }
}
