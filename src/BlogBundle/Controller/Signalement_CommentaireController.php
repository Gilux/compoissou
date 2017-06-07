<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Signalementcommentaire;
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

        $signalement_Commentaires = $em->getRepository('BlogBundle:Signalementcommentaire')->findByUtilisateur($this->getUser());

        return $this->render('BlogBundle:Signalement_Commentaire:index.html.twig', array(
            'signalement_Commentaires' => $signalement_Commentaires,
        ));
    }

    /**
     * Creates a new signalement_Commentaire entity.
     *
     */
    public function newAction(Request $request, $idArticle, $idComm)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BlogBundle:Article')->find($idArticle);

        //Erreur si l'article ne correspond pas au commentaire
        $commentaires = $article->getCommentaires();
        $tab = array();
        foreach($commentaires as $comm)
            array_push($tab, $comm->getId());
        if(!in_array($idComm, $tab))
            throw $this->createAccessDeniedException('Commentaire introuvable !');

        $signalement_Commentaire = new Signalementcommentaire();
        $form = $this->createForm('BlogBundle\Form\SignalementcommentaireType', $signalement_Commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $commentaire = $em->getRepository('BlogBundle:Commentaire')->findById($idComm);
            $signalement_Commentaire->setCommentaire($commentaire[0]);
            $signalement_Commentaire->setUtilisateur($this->getUser());

            $em->persist($signalement_Commentaire);
            $em->flush();

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = \Symfony\Component\Security\Acl\Domain\ObjectIdentity::fromDomainObject($signalement_Commentaire);
            $acl = $aclProvider->createAcl($objectIdentity);
            $user = $this->getUser();
            $securityIdentity = \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity::fromAccount($user);
            $acl->insertObjectAce($securityIdentity, \Symfony\Component\Security\Acl\Permission\MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

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
    public function editAction(Request $request, Signalementcommentaire $signalement_Commentaire)
    {
        $this->denyAccessUnlessGranted('EDIT', $signalement_Commentaire, 'Vous ne pouvez pas éditer ce signalement !');
        if(!$this->isGranted('EDIT', $signalement_Commentaire) && !$this->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException('Impossible de visionner cette page.');

        $deleteForm = $this->createDeleteForm($signalement_Commentaire);
        $editForm = $this->createForm('BlogBundle\Form\SignalementcommentaireType', $signalement_Commentaire);
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
    public function deleteAction(Request $request, Signalementcommentaire $signalement_Commentaire)
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
    private function createDeleteForm(Signalementcommentaire $signalement_Commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('signalement_delete', array('id' => $signalement_Commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    public function messignalementsAction(){

        $em = $this->getDoctrine()->getManager();

        $signalement_Commentaires = $em->getRepository('BlogBundle:Signalement_Commentaire')->findByUtilisateur($this->getUser());

        return $this->render('BlogBundle:Signalement:list.html.twig', array(
            'signalements_Commentaires' => $signalement_Commentaires,
        ));
    }
}
