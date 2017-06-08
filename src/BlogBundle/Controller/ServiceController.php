<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceController extends Controller
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function peutLireArticle(User $utilisateur, Article $article)
    {
        $themes = $article->getThemes();

        $em = $this->container->get("doctrine")->getManager();
        $choixthemeRepository = $em->getRepository("BlogBundle:Choixtheme");

        // Récupère une liste d'objets Theme, pour chacun on va vérifier si un objet Choixtheme correspond à l'auteur et au thème. Si oui, c'est bon
        foreach($themes as $theme)
        {
            $choixtheme = $choixthemeRepository->findByUserAndTheme($utilisateur,$theme);

            if(!is_null($choixtheme))
            {
                return true;
            }
        }

        return false;
    }

    public function peutCommenterArticle(Utilisateur $utilisateur, Article $article)
    {
        $themes = $article->getThemes();

        $em = $this->getDoctrine()->getManager();
        $choixthemeRepository = $em->getRepository("BlogBundle:Choixtheme");

        // Récupère une liste d'objets Theme, pour chacun on va vérifier si un objet Choixtheme correspond à l'auteur et au thème. Si oui, c'est bon
        foreach($themes as $theme)
        {
            $choixtheme = $choixthemeRepository->findByUserAndTheme($utilisateur,$theme);

            if(!is_null($choixtheme) && $choixtheme->getExpert())
            {
                return true;
            }
        }

        return false;
    }

}
