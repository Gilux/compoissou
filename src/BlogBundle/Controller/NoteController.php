<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    public function addAction()
    {
        $reponse = array("success" => true);

        $note = $_POST["note"];

        // TODO : check sécurité (un user ne peut noter qu'un article s'il en a les droits)

        $newNote = new Note();
        $newNote->setNote($note);
        $newNote->setUtilisateur($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($newNote);
        $em->flush();


        return new JsonResponse(
            $reponse,
            JsonResponse::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }

}
