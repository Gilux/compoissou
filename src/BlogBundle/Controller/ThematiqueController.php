<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Choixtheme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ThematiqueController extends Controller
{
    public function listAction()
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $user = $this->getUser();

        $userThemes = $user->getChoixthemes();

        $listeThemes = $em->getRepository("BlogBundle:Theme")->findAll();

        return $this->render('BlogBundle:Thematique:list.html.twig', array(
            "userThemes" => $userThemes,
            "listeThemes" => $listeThemes
        ));
    }

    public function searchAction($str)
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $allThematiques = $em->getRepository("BlogBundle:Theme")->createQueryBuilder('theme')
        ->where('theme.nom LIKE :nom')
            ->setParameter('nom', '%'.$str.'%')
            ->getQuery();
        $allThematiques = $allThematiques->getResult();

        $userThematiquesIDs = array();

        $userThematiques = $em->getRepository("BlogBundle:Choixtheme")->findByUser($this->getUser());
        foreach($userThematiques as $userThematique)
        {
            $expert = 0;
            if($userThematique->getExpert() == 1)
            {
                $expert = 1;
            }
            $userThematiquesIDs[$userThematique->getTheme()->getId()] = $expert;
        }

        //dump($userThematiquesIDs); die();

        $reponse = array();

        foreach($allThematiques as $t)
        {
            $lecteur = false;
            $expert = false;

            // Si la thématique fait partie de celles de l'utilisateur
            if(array_key_exists($t->getId(),$userThematiquesIDs))
            {
                $lecteur = true;
                if($userThematiquesIDs[$t->getId()] == 1)
                {
                    $expert = true;
                }

            }
            $reponse[$t->getId()] = array("nom" => $t->getNom(), "description" => $t->getDescription(), "lecteur" => $lecteur, "expert" => $expert);
        }

        return new JsonResponse(
            $reponse,
            JsonResponse::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }

    // $id = ID de la thématique à modifier, et 2 paramètres POST attendus : lecteur et expert
    public function changestatutAction()
    {
        $thematiqueID = $_POST["thematique"];
        $lecteur = $_POST["lecteur"];
        $expert = $_POST["expert"];

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $choixthemeRepository = $em->getRepository("BlogBundle:Choixtheme");
        $thematiqueRepository = $em->getRepository("BlogBundle:Theme");
        $thematique = $thematiqueRepository->find($thematiqueID);

        // TODO : Check sécurité, est-ce que la personne a le droit de bien modifier ces éléments ?
        if($lecteur == 0)
        {
            $choixtheme = $choixthemeRepository->findByUserAndTheme($this->getUser(),$thematique);
            if(!is_null($choixtheme))
            {
                $em->remove($choixtheme);
                $em->flush();
            }
        }
        else
        {
            $choixtheme = $choixthemeRepository->findByUserAndTheme($this->getUser(),$thematique);
            if(!is_null($choixtheme)) // Si l'entité choix existe déjà (déjà lecteur mais pas expert par exemple) on update
            {
                $choixtheme->setExpert($expert);
                $em->persist($choixtheme);
                $em->flush();
            }
            else // Créer l'entité "choix"
            {
                $choixtheme = new Choixtheme();
                $choixtheme->setExpert($expert);
                $choixtheme->setTheme($thematique);
                $choixtheme->setUser($this->getUser());
                $em->persist($choixtheme);
                $em->flush();
            }
        }

        $reponse = $_POST;

        return new JsonResponse(
            $reponse,
            JsonResponse::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }

}
