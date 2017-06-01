<?php

namespace BlogBundle\Entity;

/**
 * Commentaire
 */
class Commentaire
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var User
     */
    private $utilisateur;

    private $signalements_commentaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->signalements_commentaire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param User $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @var Article
     */
    private $article;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commentaire
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function addSignalementCommentaire(\BlogBundle\Entity\Signalement_Commentaire $signalements_commentaire)
    {
        $this->signalements_commentaire[] = $signalements_commentaire;

        return $this;
    }

    public function removeSignalementCommentaire(\BlogBundle\Entity\Signalement_Commentaire $signalements_commentaire)
    {
        $this->signalements_commentaire->removeElement($signalements_commentaire);
    }

    public function getSignalementCommentaire()
    {
        return $this->signalements_commentaire;
    }
}
