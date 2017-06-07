<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Article
 */
class Article
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateModif;

    private $themes;

    private $commentaires;

    private $notes;

    private $utilisateur;

    private $signalementsarticle;

    private $luPar;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->themes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->signalementsarticle = new ArrayCollection();
        $this->luPar = new ArrayCollection();
    }

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Article
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Article
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return Article
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    public function getUtilisateur(){
        return $this->utilisateur;
    }

    public function setUtilisateur($utilisateur){
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function addTheme(\BlogBundle\Entity\Article $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    public function removeTheme(\BlogBundle\Entity\Article $theme)
    {
        $this->themes->removeElement($theme);
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function addCommentaire(\BlogBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    public function removeCommentaire(\BlogBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    public function getCommentaires()
    {
        return $this->commentaires;
    }

    public function addNote(\BlogBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    public function removeNote(\BlogBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function addSignalementarticle(Signalementarticle $signalementsarticle)
    {
        $this->signalementsarticle[] = $signalementsarticle;

        return $this;
    }

    public function removeSignalementarticle(Signalementarticle $signalementsarticle)
    {
        $this->signalementsarticle->removeElement($signalementsarticle);
    }

    public function getSignalementarticle()
    {
        return $this->signalementsarticle;
    }

    public function getLecteurs()
    {
        return $this->luPar;
    }

    public function addLecteur(User $user)
    {
        $this->luPar[] = $user;
    }

    public function removeLecteur(User $user)
    {
        $this->luPar->removeElement($user);
    }

}
