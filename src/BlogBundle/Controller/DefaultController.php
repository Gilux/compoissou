<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

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

            $articles_raw = array();

            if($this->isGranted("ROLE_CRITIQUE"))
            {
                $now = new \DateTime();
                $now->modify("-30 days");

                $articles_raw = $repositoryArticle->findAfter($now);
            }
            else if($this->isGranted("ROLE_LECTEUR"))
            {
                if(isset($_GET['tri']))
                {
                    if($_GET['tri'] == 'note')
                    {
                        $articles_raw = $repositoryArticle->findAll();
                        echo "note";

                    }
                    else if($_GET['tri'] == 'date')
                    {
                        echo "date";
                        $articles_raw = $repositoryArticle->findBy(array(),array('dateCreation' => "desc"));
                    }
                    else if($_GET['tri'] == "auteur")
                    {
                        echo "auteur";
                        $articles_raw = $repositoryArticle->findBy(array(),array('auteur' => "asc"));
                    }
                }
                else
                {
                    $articles_raw = $repositoryArticle->findAll();
                }

            }

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
