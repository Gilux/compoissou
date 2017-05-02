<?php

namespace ProjetblogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProjetblogBundle:Default:index.html.twig');
    }
}
