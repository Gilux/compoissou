<?php

namespace BlogBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Choixtheme
 */
class Choixtheme
{
    /**
     * @var int
     */
    private $id;

    /**
    *  @var Theme
    */
    private $theme;

    /**
     *  @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $expert;


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
     * Set expert
     *
     * @param boolean $expert
     *
     * @return Choixtheme
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return bool
     */
    public function getExpert()
    {
        return $this->expert;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param Theme $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
}

