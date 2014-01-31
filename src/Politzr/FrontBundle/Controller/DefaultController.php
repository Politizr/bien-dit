<?php

namespace Politzr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PolitzrFrontBundle:Default:index.html.twig', array('name' => $name));
    }
}
