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
        if($this->isGranted("ROLE_AUTEUR"))
        {
            $repositoryArticle = $em->getRepository("BlogBundle:Article");
            $articlesAuteur = $repositoryArticle->findByUtilisateur($this->getUser());
            if(count($articlesAuteur) != 0)
                $retour = array("articlesAuteur" => $articlesAuteur);
        }
        else if($this->isGranted("ROLE_LECTEUR") || $this->isGranted("ROLE_CRITIQUE"))
        {
            $repositoryArticle = $em->getRepository("BlogBundle:Article");
            $articles_raw = $repositoryArticle->findAll();

            $serviceController = $this->get('app.serviceController');

            $articles = array();
            foreach($articles_raw as $article)
            {
                if(!$this->getUser()->aLuArticle($article) && $serviceController->peutLireArticle($this->getUser(),$article))
                {
                    $articles[] = $article;
                }
            }
            $retour = array("articlesAuteur" => $articles);
        }


        return $this->render('BlogBundle:Default:index.html.twig', $retour);
    }
}
