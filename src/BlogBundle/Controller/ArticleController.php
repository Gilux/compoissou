<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{


    /**
     * Lists all article entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Articles de l'utilisateur
        $articles = $em->getRepository('BlogBundle:Article')->findByUtilisateur($this->getUser());
        //notes moyennes de tous les articles de l'auteur
        $total = 0;
        $count = 0;
        $moyenne = 0;
        if(count($articles) != 0)
        {
            foreach ($articles as $article)
            {
                $notes = $article->getNotes();

                $count += count($notes);
                foreach($notes as $note)
                {
                    $total += $note->getNote();
                }
            }

            if($count != 0)
                $moyenne = $total/$count;
        }

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
            'moyenne' => $moyenne
        ));
    }

    /**
     * Creates a new article entity.
     *
     */
    public function newAction(Request $request)
    {
        //$authorizationChecker = $this->get('security.authorization_checker');

        $article = new Article();
        $form = $this->createForm('BlogBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDateCreation(new \DateTime());
            $article->setDateModif(new \DateTime());
            $article->setUtilisateur($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = \Symfony\Component\Security\Acl\Domain\ObjectIdentity::fromDomainObject($article);
            $acl = $aclProvider->createAcl($objectIdentity);
            $user = $this->getUser();
            $securityIdentity = \Symfony\Component\Security\Acl\Domain\UserSecurityIdentity::fromAccount($user);
            $acl->insertObjectAce($securityIdentity, \Symfony\Component\Security\Acl\Permission\MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     */
    public function showAction(Article $article, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $serviceController = $this->get('app.serviceController');
        /* Afficher l'article si :
         *  - l'utilisateur à le droit de vision (ACE)
         *  - l'utilisateur à le rôle ROLE_LECTEUR
         * (Grâce à la hiérarchie les Critiques et les Admin peuvent le voir aussi)
         */
        if(!$this->isGranted('VIEW', $article) && $serviceController->getRole($this->getUser()) == 'ROLE_AUTEUR')
            throw $this->createAccessDeniedException('Impossible de visionner cette page.');

        // On appelle le service app.serviceController et sa fonction peutLireArticle qui va comparer les choix de catégories de l'utilisateur et celles de l'article
        if(!$serviceController->peutLireArticle($this->getUser(),$article) && $this->getUser()->getId() != $article->getUtilisateur()->getId())
        {
            throw $this->createAccessDeniedException('Impossible de visionner cet article, il ne fait pas partie de vos catégories.');
        }

        $deleteForm = $this->createDeleteForm($article);

        $notes = $article->getNotes();

        $total = 0;

        foreach($notes as $note)
        {
            $total += $note->getNote();
        }

        if(count($notes) != 0)
        {
            $moyenne = $total/count($notes);
        }
        else
        {
            $moyenne = null;
        }


        $commentaire = new Commentaire();
        $form = $this->createForm('BlogBundle\Form\CommentaireType', $commentaire);
        $form->handleRequest($request);
        $serviceController = $this->get('app.serviceController');

        // 1ère vérification : est-ce que l'utilisateur a bien le rôle "critique"
        if ($form->isSubmitted() && $form->isValid() && $serviceController->getRole($this->getUser()) == 'ROLE_CRITIQUE') {
            // On vérifie que l'utilisateur est bien en droit de poster un commentaire ici
            $droitPost = false;

            // Si l'utilisateur ne commente pas sur son propre article, et qu'il est bien en mesure de commenter en fonction des catégories choisies
            if($serviceController->peutCommenterArticle($this->getUser(),$article) && $this->getUser()->getId() != $article->getUtilisateur()->getId())
            {
                $droitPost = true;
            }


            if($droitPost)
            {
                $commentaire->setDate(new \DateTime());
                $commentaire->setArticle($article);
                $commentaire->setUtilisateur($this->getUser());

                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaire);
                $em->flush();
            }
            else
            {
                throw $this->createAccessDeniedException('Impossible de commenter cet article.');
            }

        }

        //C'est le propriétaire de l'article
        if($this->isGranted('VIEW', $article))
            $lu = false;
        else
            $lu = $this->getUser()->aLuArticle($article);

        return $this->render('BlogBundle:Article:show.html.twig', array(
            'article' => $article,
            'moyenne' => round($moyenne,2),
            'edit_form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'lu' => $lu
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     */
    public function editAction(Request $request, Article $article)
    {
        $this->denyAccessUnlessGranted('EDIT', $article, 'Vous ne pouvez pas éditer cet article !');

        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('BlogBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $article->setDateModif(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     */
    public function deleteAction(Request $request, Article $article)
    {
        $this->denyAccessUnlessGranted('DELETE', $article, 'Vous ne pouvez pas supprimer cet article !');

        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }


    public function luAction($article_id)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $ArticleRepository = $entityManager->getRepository('BlogBundle:Article');
       $article = $ArticleRepository->find($article_id);

       if(!$this->getUser()->aLuArticle($article))
       {
            $article->addLecteur($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

       }
       else
       {
            $article->removeLecteur($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();
       }
        return $this->redirectToRoute("blog_homepage");
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
