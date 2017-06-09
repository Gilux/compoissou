<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilisateurController extends Controller
{
    public function profilAction($id)
    {
        $user = $this->getUser();

        $roles = $user->getRoles();

        return $this->render('BlogBundle:Utilisateur:profil.html.twig', array(
            "id" => $id,
            "test" => "ISSOU",
            "roles" => $roles
        ));
    }

    public function widgetAction()
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        $strRoles = array();
        foreach($roles as $role)
        {
            $strRoles[] = $role->getRole();
        }

        return $this->render('BlogBundle:Utilisateur:widget.html.twig', array(
            "user" => $user,
            "strRoles" => implode(", ",$strRoles)
        ));
    }

    public function peutLireArticle(Utilisateur $utilisateur, Article $article)
    {
        $themes = $article->getThemes();

        $em = $this->getDoctrine()->getManager();
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

    /*
     * notesAction
     * Liste l'ensemble des notes et commentaires de l'utilisateur courant, s'il est critique
     */
    public function notesAction()
    {
        echo 'notes';
        die();
    }

}
