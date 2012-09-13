<?php

namespace Fernando\Bundle\SpritesBundle\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Description of SpritesFilter
 */
class FakeSpriteFilter implements FilterInterface
{
    /**
     * Filters an asset after it has been loaded.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterLoad(AssetInterface $asset)
    {
    }

    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    public function filterDump(AssetInterface $asset)
    {
    }
}
