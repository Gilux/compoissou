<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Signalementarticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Signalementarticle controller.
 *
 */
class SignalementarticleController extends Controller
{
    /**
     * Lists all signalementarticle entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $signalementarticles = $em->getRepository('BlogBundle:Signalementarticle')->findByUtilisateur($this->getUser());

        return $this->render('BlogBundle:signalementarticle:index.html.twig', array(
            'signalementarticles' => $signalementarticles,
        ));
    }

    /**
     * Creates a new signalementarticle entity.
     *
     */
    public function newAction(Request $request, $idArticle)
    {
        $signalementarticle = new Signalementarticle();
        $form = $this->createForm('BlogBundle\Form\SignalementarticleType', $signalementarticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $article = $em->getRepository('BlogBundle:Article')->find($idArticle);
            $signalementarticle->setArticle($article);
            $signalementarticle->setUtilisateur($this->getUser());

            $em->persist($signalementarticle);
            $em->flush();

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = \Symfony\Component\Security\Acl\Domain\ObjectIdentity::fromDomainObject($signalementarticle);
            $acl = $aclProvider->createAcl($objectIdentity);
            $user = $this->getUser();
            $securityIdentity = \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity::fromAccount($user);
            $acl->insertObjectAce($securityIdentity, \Symfony\Component\Security\Acl\Permission\MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            return $this->redirectToRoute('signalementarticle_index');
        }

        return $this->render('BlogBundle:signalementarticle:new.html.twig', array(
            'signalementarticle' => $signalementarticle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing signalementarticle entity.
     *
     */
    public function editAction(Request $request, Signalementarticle $signalementarticle)
    {
        $deleteForm = $this->createDeleteForm($signalementarticle);
        $editForm = $this->createForm('BlogBundle\Form\SignalementarticleType', $signalementarticle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('signalementarticle_edit', array('id' => $signalementarticle->getId()));
        }

        return $this->render('BlogBundle:signalementarticle:edit.html.twig', array(
            'signalementarticle' => $signalementarticle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a signalementarticle entity.
     *
     */
    public function deleteAction(Request $request, Signalementarticle $signalementarticle)
    {
        $form = $this->createDeleteForm($signalementarticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($signalementarticle);
            $em->flush();
        }

        return $this->redirectToRoute('signalementarticle_index');
    }

    /**
     * Creates a form to delete a signalementarticle entity.
     *
     * @param Signalementarticle $signalementarticle The signalementarticle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Signalementarticle $signalementarticle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('signalementarticle_delete', array('id' => $signalementarticle->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
