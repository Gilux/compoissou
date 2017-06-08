<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $mdp;

    /**
     * @var string
     */
    private $password;

    private $articles;

    private $commentaires;

    private $notes;

    private $signalementscommentaire;

    private $signalementsarticle;

    private $avatar;

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     * @return User
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function getSalt()
    {
        return "";
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize(array($this->id,$this->login,$this->password));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->login, $this->password) = unserialize($serialized);
    }

    public function getUsername()
    {
        return $this->login;
    }


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $themeassociation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $articlesLus;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->themeassociation = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->articlesLus = new ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \BlogBundle\Entity\Role $role
     *
     * @return User
     */
    public function addRole(\BlogBundle\Entity\Role $role)
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Remove role
     *
     * @param \BlogBundle\Entity\Role $role
     */
    public function removeRole(\BlogBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    public function addChoixtheme(Choixtheme $choixtheme)
    {
        $this->themeassociation[] = $choixtheme;
    }

    public function removeChoixtheme(Choixtheme $choixtheme)
    {
        $this->themeassociation->removeElement($choixtheme);
    }

    public function getChoixthemes()
    {
        return $this->themeassociation->toArray();;
    }

    public function getChoixthemesToCollection()
    {
        return $this->themeassociation;
    }

    public function addArticle(\BlogBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    public function removeArticle(\BlogBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function addArticlelu(\BlogBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    public function removeArticlelu(\BlogBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    public function getArticleslus()
    {
        return $this->articles;
    }

    public function aLuArticle(Article $article)
    {
        return $this->articlesLus->contains($article);
    }

    public function addNote(\BlogBundle\Entity\Note $note)
    {
        $this->notes[] = $note;
    }

    public function removeNote(\BlogBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function __toString()
    {
        return $this->prenom . " " . $this->nom;
    }
}
