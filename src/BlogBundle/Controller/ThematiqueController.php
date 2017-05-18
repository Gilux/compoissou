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

        //$themeRisitas = $em->getRepository("BlogBundle:Theme")->find(4);
        //$user->addTheme($themeRisitas);
        //$em->persist($user);
        //$em->flush();

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


        $reponse = array();

        foreach($allThematiques as $t)
        {
            $reponse[$t->getId()] = array("nom" => $t->getNom());
        }

        return new JsonResponse(
            $reponse,
            JsonResponse::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }

}
