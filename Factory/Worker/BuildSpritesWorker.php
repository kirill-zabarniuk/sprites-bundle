<?php

namespace Fernando\Bundle\SpritesBundle\Factory\Worker;

use Assetic\Asset\AssetCollectionInterface;
use Assetic\Asset\AssetInterface;
use Assetic\Asset\StringAsset;
use Assetic\Factory\Worker\WorkerInterface;
use Fernando\Bundle\SpritesBundle\Filter\FakeSpriteFilter;
use Fernando\Bundle\SpritesBundle\Sprite\SpriteManager;

/**
 * Собирает спрайт прежде, чем AssetCollection пропустит каждый файл через фильтр и склеит изображения с помощью \n
 * Заменяет изображения в коллекции на css стили (можно склеивать)
 */
class BuildSpritesWorker implements WorkerInterface
{
    private $manager;

    /**
     * Конструктор
     * 
     * @param \Fernando\Bundle\SpritesBundle\Sprite\SpriteManager $sm SpriteManaпer
     */
    public function __construct(SpriteManager $sm)
    {
        $this->manager = $sm;
    }

    /**
     * Sprite manager
     *
     * @return SpriteManager
     */
    private function getSpriteManager()
    {
        return $this->manager;
    }


    /**
     * Processes an asset.
     *
     * @param AssetInterface $asset An asset
     *
     * @return AssetInterface|null May optionally return a replacement asset
     */
    public function process(AssetInterface $asset)
    {
        if (!$asset instanceof AssetCollectionInterface) {
            return;
        }

        /* @var $asset AssetCollectionInterface */
        $hasSpriteFilter = false;
        foreach ($asset->getFilters() as $filter) {
            if ($filter instanceof FakeSpriteFilter) {
                $hasSpriteFilter = true;
                break;
            }
        }

        if (!$hasSpriteFilter) {
            return;
        }

        // добавление файлов в SpriteManager
        $sm = $this->getSpriteManager();
        foreach ($asset->all() as $leaf) {
            /* @var $leaf \Assetic\Asset\FileAsset */
            $sm->addFile(realpath(
                $leaf->getSourceRoot() . DIRECTORY_SEPARATOR . $leaf->getSourcePath()
            ));
            $asset->removeLeaf($leaf);
        }

        // сборка спрайтов
        $sm->processFiles();

        // замена asset-ов в коллекции
        $asset->clearFilters();
        $asset->setTargetPath($sm->getCssManager()->getCssDir() . DIRECTORY_SEPARATOR . $sm->getCssManager()->getFilename());
        $asset->add(new StringAsset($sm->getCssManager()->getCss(), array(
            new \Assetic\Filter\CssRewriteFilter(),
        )));

        return $asset;
    }
}
