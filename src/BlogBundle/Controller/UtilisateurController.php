<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilisateurController extends Controller
{
    public function profilAction($id)
    {
        return $this->render('BlogBundle:Utilisateur:profil.html.twig', array(
            "id" => $id,
            "test" => "ISSOU"
        ));
    }

}
