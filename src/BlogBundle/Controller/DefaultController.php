<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $retour = array();

        /**
         * Si l'utilisateur à le ROLE_AUTEUR
         */
        $repositoryArticle = $em->getRepository("BlogBundle:Article");
        $articlesAuteur = $repositoryArticle->findByAuteur($this->getUser()->getId());
        if(count($articlesAuteur) != 0)
            $retour = array("articlesAuteur" => $articlesAuteur);

        return $this->render('BlogBundle:Default:index.html.twig', $retour);
    }
}
