<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $serviceController = $this->get('app.serviceController');
        if($serviceController->peutCommenterArticle($this->getUser(),$article) && $this->isGranted("ROLE_CRITIQUE"))
        {
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
        }
        else
        {
            $reponse["status"] = "403";
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

    /*
     * notesAction
     * Liste l'ensemble des notes et commentaires de l'utilisateur courant, s'il est critique
     */
    public function notesAction()
    {
        $em = $this->get("doctrine.orm.entity_manager");
        $noteRepository = $em->getRepository("BlogBundle:Note");
        $notes = $noteRepository->findByUtilisateur($this->getUser());

        $commentaireRepository = $em->getRepository("BlogBundle:Commentaire");
        $commentaires = $commentaireRepository->findByUtilisateur($this->getUser());

        $deleteFormsComs = array();
        foreach ($commentaires as $commentaire) {
            $deleteFormsComs[$commentaire->getId()] = $this->createComDeleteForm($commentaire)->createView();
        }

        $deleteFormsNotes = array();
        foreach ($notes as $note) {
            $deleteFormsNotes[$note->getId()] = $this->createDeleteForm($note)->createView();
        }


        return $this->render('BlogBundle:Note:list.html.twig', array(
            "notes" => $notes,
            "commentaires" => $commentaires,
            'deleteFormsNotes' => $deleteFormsNotes,
            "deleteFormsComs" => $deleteFormsComs
        ));
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Note $note The note entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Note $note)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('note_delete', array('id' => $note->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Note $note The note entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createComDeleteForm(Commentaire $commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentaire_delete', array('id' => $commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }


    /**
     * Deletes a note entity.
     *
     */
    public function deleteAction(Request $request, Note $note)
    {
        if($note->getUtilisateur()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("/");
        }

        $form = $this->createDeleteForm($note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($note);
            $em->flush();
        }

        return $this->redirectToRoute('mes_notes');
    }

}
