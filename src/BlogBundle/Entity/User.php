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
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->themeassociation = new \Doctrine\Common\Collections\ArrayCollection();
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
}
