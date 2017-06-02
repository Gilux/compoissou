<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    public function addAction($id)
    {
        $note = $_POST["note"];

        // TODO : check sécurité (un user ne peut noter qu'un article s'il en a les droits)

        $manager = $this->getDoctrine()->getManager();

        $articleRepository = $manager->getRepository('BlogBundle:Article');
        $noteRepository = $manager->getRepository('BlogBundle:Note');

        $article = $articleRepository->findOneById($id);

        $noteObj = $noteRepository->findByUserAndArticle($this->getUser(),$article);

        $em = $this->getDoctrine()->getManager();

        $reponse["status"] = null;


        // Si l'utilisateur n'a pas déjà noté cet article
        if(is_null($noteObj))
        {
            $newNote = new Note();
            $newNote->setNote($note);
            $newNote->setUtilisateur($this->getUser());
            $newNote->setArticle($article);
            $em->persist($newNote);
            $em->flush();
            $reponse["status"] = "create";
        }
        else // Sinon il s'agit d'une modification, on met la note à jour
        {
            $noteObj->setNote($note);
            $em->persist($noteObj);
            $em->flush();
            $reponse["status"] = "update";
        }


        $notes = $article->getNotes();
        $total = 0;
        foreach($notes as $note)
        {
            $total += $note->getNote();
        }
        if(count($notes) != 0)
        {
            $moyenne = round($total/count($notes),2);
        }
        else
        {
            $moyenne = null;
        }

        $reponse["moyenne"] = $moyenne;

        return new JsonResponse(
            $reponse,
            JsonResponse::HTTP_OK,
            array('content-type' => 'application/json')
        );
    }

}
