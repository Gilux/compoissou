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

        $recherche = null;
        if(isset($_POST["recherche"]))
        {
            $recherche = $_POST["recherche"];
        }

        if($rolechoisi == "ROLE_AUTEUR")
        {
            $articles_raw = $repositoryArticle->findByUtilisateur($this->getUser());
        }
        else if($rolechoisi == "ROLE_CRITIQUE")
        {
            $now = new \DateTime();
            $now->modify("-30 days");

            $recherche = null;
            if(isset($_POST["recherche"]))
            {
                $recherche = $_POST["recherche"];
            }

            $articles_raw = $repositoryArticle->findRecherche($recherche,$now);
        }
        else if($rolechoisi == "ROLE_LECTEUR")
        {
            $articles_raw = $repositoryArticle->findRecherche($recherche,null);
        }
        else if($rolechoisi == "ROLE_ADMIN")
        {
            $articles_raw = $repositoryArticle->findAll();
        }


        $serviceController = $this->get('app.serviceController');

        $articles = array();

        foreach($articles_raw as $article)
        {
            if(!$this->getUser()->aLuArticle($article) && $serviceController->peutLireArticle($this->getUser(),$article) || $rolechoisi == "ROLE_AUTEUR" || $rolechoisi == "ROLE_ADMIN")
            {
                $articles[] = $article;
            }
        }

        if(isset($_GET['tri']))
        {
            if($_GET['tri'] == 'note')
            {
                //echo "note";
                $noteRepository = $em->getRepository("BlogBundle:Note");

                foreach($articles as $article)
                {
                    $notes = $noteRepository->findByArticle($article);

                    $total = 0;

                    if(count($notes) != 0)
                    {
                        foreach ($notes as $note)
                        {
                            $total += $note->getNote();
                        }
                        $moyenne = $total / count($notes);
                    }
                    else
                    {
                        $moyenne = 2.5;
                    }

                    $article->setMoyenne($moyenne);

                    //echo $article->getTitre() . " : " . $article->getMoyenne() . "<br>";
                }

                usort($articles,function($a,$b){
                    //echo $a->getTitre() . " - " . $b->getTitre() . " " . ($a->getMoyenne() <=> $b->getMoyenne()) . "<br>";

                    if($a->getMoyenne() == $b->getMoyenne())
                    {
                        return 0;
                    }
                    else
                    {
                        if(isset($_GET["sens"]) && $_GET["sens"] == "desc")
                        {
                            return $a->getMoyenne() < $b->getMoyenne();
                        }
                        else
                        {
                            return $a->getMoyenne() > $b->getMoyenne();
                        }

                    }

                });
            }
            else if($_GET['tri'] == 'date')
            {
                usort($articles,function($a,$b){
                    //echo $a->getTitre() . " - " . $b->getTitre() . " " . ($a->getMoyenne() <=> $b->getMoyenne()) . "<br>";

                    if($a->getDatecreation() == $b->getDatecreation())
                    {
                        return 0;
                    }
                    else
                    {
                        if(isset($_GET["sens"]) && $_GET["sens"] == "desc")
                        {
                            return $a->getDatecreation() < $b->getDatecreation();
                        }
                        else
                        {
                            return $a->getDatecreation() > $b->getDatecreation();
                        }

                    }

                });
            }
            else if($_GET['tri'] == "auteur")
            {
                usort($articles,function($a,$b){
                    //echo $a->getTitre() . " - " . $b->getTitre() . " " . ($a->getMoyenne() <=> $b->getMoyenne()) . "<br>";

                    if($a->getUtilisateur()->getNom() == $b->getUtilisateur()->getNom())
                    {
                        return 0;
                    }
                    else
                    {
                        if(isset($_GET["sens"]) && $_GET["sens"] == "desc")
                        {
                            return $a->getUtilisateur()->getNom() < $b->getUtilisateur()->getNom();
                        }
                        else
                        {
                            return $a->getUtilisateur()->getNom() > $b->getUtilisateur()->getNom();
                        }

                    }

                });

            }
        }


        return $this->render('BlogBundle:Default:index.html.twig', array("articles" => $articles, "recherche" => $recherche));
    }
}
