<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add('fernando_sprites_homepage', new Route('/hello/{name}', array(
    '_controller' => 'FernandoSpritesBundle:Default:index',
)));

return $collection;
