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
        $service = $this->get("app.servicecontroller");
        $rolechoisi = $service->getRole($this->getUser());

        $repositoryArticle = $em->getRepository("BlogBundle:Article");

        $articles_raw = array();

        if($rolechoisi == "ROLE_AUTEUR")
        {
            $articles_raw = $repositoryArticle->findByUtilisateur($this->getUser());
        }
        else if($rolechoisi == "ROLE_CRITIQUE")
        {
            $now = new \DateTime();
            $now->modify("-30 days");

            $articles_raw = $repositoryArticle->findAfter($now);
        }
        else if($rolechoisi == "ROLE_LECTEUR")
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
        else if($rolechoisi == "ROLE_ADMIN")
        {
            $articles_raw = $repositoryArticle->findAll();
            return $this->render('BlogBundle:Default:index.html.twig', array("articles" => $articles));
        }

        $serviceController = $this->get('app.serviceController');

        $articles = array();
        foreach($articles_raw as $article)
        {
            if(!$this->getUser()->aLuArticle($article) && $serviceController->peutLireArticle($this->getUser(),$article) || $rolechoisi == "ROLE_AUTEUR")
            {
                $articles[] = $article;
            }
        }

        return $this->render('BlogBundle:Default:index.html.twig', array("articles" => $articles));
    }
}
