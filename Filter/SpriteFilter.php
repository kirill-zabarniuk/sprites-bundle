<?php

namespace Fernando\Bundle\SpritesBundle\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Fernando\Bundle\SpritesBundle\Sprite\SpriteManager;

/**
 * Description of SpritesFilter
 */
class SpriteFilter implements FilterInterface
{
    private $manager;

    public function __construct(SpriteManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Sprites manager
     * 
     * @return SpriteManager
     */
    private function getManager()
    {
        return $this->manager;
    }

    /**
     * Filters an asset after it has been loaded.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterLoad(AssetInterface $asset)
    {
        /* @var $asset \Assetic\Asset\FileAsset */
        $this->getManager()->addFile(realpath(
            $asset->getSourceRoot() . DIRECTORY_SEPARATOR . $asset->getSourcePath()
        ));
    }

    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterDump(AssetInterface $asset)
    {
        $this->getManager()->processFiles();
    }
}
