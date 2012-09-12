<?php

namespace Fernando\Bundle\SpritesBundle\Templating;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Description of SpriteAsseticHelper
 */
class SpriteHelper extends Helper
{
    public function getName()
    {
        return 'fernando';
    }

    public function sprite($inputs = array(), $filters = array(), array $options = array())
    {
        return 'http://example.com';
    }
}
