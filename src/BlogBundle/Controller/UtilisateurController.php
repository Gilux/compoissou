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

        // Si l'utilisateur possède plusieurs rôles, on doit lui laisser le choix de celui qu'il utilise
        $multipleRoles = false;
        if(count($strRoles) > 1)
        {
            $multipleRoles = true;
        }

        // Récupérer le rôle choisi par l'utilisateur, et s'il n'y en a pas, lui en affecter un par défaut.
        $service = $this->get("app.servicecontroller");
        $rolechoisi = $service->getRole($this->getUser());

        return $this->render('BlogBundle:Utilisateur:widget.html.twig', array(
            "user" => $user,
            "strRoles" => $strRoles,
            "multipleRoles" => $multipleRoles,
            "rolechoisi" => $rolechoisi
        ));
    }

    public function choixroleAction()
    {
        $service = $this->get("app.servicecontroller");
        $service->choixRole($_POST["role"]);
        return $this->redirectToRoute("blog_homepage");
    }

}
