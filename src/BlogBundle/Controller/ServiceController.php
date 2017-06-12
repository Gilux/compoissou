<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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

    public function peutCommenterArticle(User $utilisateur, Article $article)
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

    public function choixRole($role)
    {
        $session = $this->container->get('session');
        $session->set("role_choisi",$role);
    }

    public function getRole($user)
    {
        $session = $this->container->get('session');

        if(is_null($session->get("role_choisi")))
        {
            $roles = $user->getRoles();
            $session->set("role_choisi",$roles[0]->getRole());
        }
        return $session->get("role_choisi");


    }

}
