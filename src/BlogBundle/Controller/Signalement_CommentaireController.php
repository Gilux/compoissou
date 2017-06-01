<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Signalement_Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Signalement_commentaire controller.
 *
 */
class Signalement_CommentaireController extends Controller
{
    /**
     * Lists all signalement_Commentaire entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $signalement_Commentaires = $em->getRepository('BlogBundle:Signalement_Commentaire')->findByUtilisateur($this->getUser());

        return $this->render('BlogBundle:Signalement_Commentaire:index.html.twig', array(
            'signalement_Commentaires' => $signalement_Commentaires,
        ));
    }

    /**
     * Creates a new signalement_Commentaire entity.
     *
     */
    public function newAction(Request $request, $idComm = NULL)
    {
        $signalement_Commentaire = new Signalement_commentaire();
        $form = $this->createForm('BlogBundle\Form\Signalement_CommentaireType', $signalement_Commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $commentaire = $em->getRepository('BlogBundle:Commentaire')->findById($idComm);
            $signalement_Commentaire->setCommentaire($commentaire[0]);
            $signalement_Commentaire->setUtilisateur($this->getUser());

            $em->persist($signalement_Commentaire);
            $em->flush();

            return $this->redirectToRoute('signalement_index');
        }

        return $this->render('BlogBundle:Signalement_Commentaire:new.html.twig', array(
            'signalement_Commentaire' => $signalement_Commentaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing signalement_Commentaire entity.
     *
     */
    public function editAction(Request $request, Signalement_Commentaire $signalement_Commentaire)
    {
        $deleteForm = $this->createDeleteForm($signalement_Commentaire);
        $editForm = $this->createForm('BlogBundle\Form\Signalement_CommentaireType', $signalement_Commentaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('signalement_edit', array('id' => $signalement_Commentaire->getId()));
        }

        return $this->render('BlogBundle:Signalement_Commentaire:edit.html.twig', array(
            'signalement_Commentaire' => $signalement_Commentaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a signalement_Commentaire entity.
     *
     */
    public function deleteAction(Request $request, Signalement_Commentaire $signalement_Commentaire)
    {
        $form = $this->createDeleteForm($signalement_Commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($signalement_Commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('signalement_index');
    }

    /**
     * Creates a form to delete a signalement_Commentaire entity.
     *
     * @param Signalement_Commentaire $signalement_Commentaire The signalement_Commentaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Signalement_Commentaire $signalement_Commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('signalement_delete', array('id' => $signalement_Commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
