<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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

    private $description;

    private $themeassociation;

    private $articles;

    public function __construct()
    {
        $this->themeassociation = new ArrayCollection();
        $this->articles = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }



    public function getArticles(){
        return $this->articles;
    }

    public function addArticle($article)
    {
        $this->articles[] = $article;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
