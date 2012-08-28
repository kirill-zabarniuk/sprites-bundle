<?php

namespace Fernando\Bundle\SpritesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FernandoSpritesBundle:Default:index.html.twig', array('name' => $name));
    }
}
