<?php

namespace BlogBundle\Controller;

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

}
