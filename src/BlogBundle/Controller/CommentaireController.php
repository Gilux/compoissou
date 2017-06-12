<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{

    /**
     * Deletes a article entity.
     *
     */
    public function deleteAction(Request $request, Commentaire $commentaire)
    {
        if($commentaire->getUtilisateur()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("/");
        }

        $form = $this->createDeleteForm($commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('mes_notes');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Note $note The note entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm(Commentaire $commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentaire_delete', array('id' => $commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
