<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SignalementController extends Controller
{
    public function listAction()
    {
        return $this->render('BlogBundle:Signalement:list.html.twig', array(
            // ...
        ));
    }

    public function addAction()
    {
        return $this->render('BlogBundle:Signalement:add.html.twig', array(
            // ...
        ));
    }

}
