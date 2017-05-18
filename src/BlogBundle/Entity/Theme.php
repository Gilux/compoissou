<?php

namespace BlogBundle\Entity;

/**
 * Theme
 */
class Theme
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nom;


    private $themeassociation;

    private $article;

    public function __construct()
    {
        $this->themeassociation = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Theme
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function getArticle(){
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
