<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ThematiqueController extends Controller
{
    public function listAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BlogBundle:Theme')
        ;

        $listeThemes = $repository->findAll();

        return $this->render('BlogBundle:Thematique:list.html.twig', array(
            "listeThemes" => $listeThemes
        ));
    }

}
