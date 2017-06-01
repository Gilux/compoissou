<?php

namespace BlogBundle\Entity;

/**
 * Signalementcommentaire
 */
class Signalementcommentaire
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $contenu;

    private $commentaire;

    private $utilisateur;

    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getUtilisateur()
    {
        return $this->utilisateur;
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Signalementcommentaire
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
}
