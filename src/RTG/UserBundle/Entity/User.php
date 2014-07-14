<?php

namespace RTG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="RTG\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
     * @ORM\Column(type="date", nullable=true)
     * @ORM\JoinColumn(name="birthday")
     */
    protected $birthday;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @ORM\JoinColumn(name="city")
     */
    protected $city;
    
    /**
     * @ORM\OneToOne(targetEntity="RTG\UserBundle\Entity\Avatar", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $avatar;
    
    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsArticle", mappedBy="author")
     */
    protected $articles;
    
    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsComment", mappedBy="user")
     */
    protected $comments;
    
    /**
     * @ORM\Column(type="boolean")
     * @ORM\JoinColumn(name="newsletter")
     */
    protected $newsletter;
  
    public function __construct()
    {
        parent::__construct();
        $this->newsletter = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * Set birthday
     *
     * @var \Date
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }
    
    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * Set city
     *
     * @var string
     * @return User 
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
    /**
     * Compute age
     *
     * @return string
     */
    public function getAge()
    {
        if($this->birthday == null) {
            return null;
        } else {
            $now = new \DateTime();
            $interval = $now->diff($this->birthday);
            if($interval->y > 0) {
                return $interval->y.' ans';
            } elseif($interval->m > 0) {
                return $interval->m.' mois';
            } elseif($interval->d > 0) {
                return $interval->d.' jours';
            }
        }
    }

    /**
     * Add articles
     *
     * @param \RTG\BlogBundle\Entity\NewsArticle $articles
     * @return User
     */
    public function addArticle(\RTG\BlogBundle\Entity\NewsArticle $articles)
    {
        $this->articles[] = $articles;
    
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \RTG\BlogBundle\Entity\NewsArticle $articles
     */
    public function removeArticle(\RTG\BlogBundle\Entity\NewsArticle $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    /**
     * Add comments
     *
     * @param \RTG\BlogBundle\Entity\NewsComment $comments
     * @return User
     */
    public function addComment(\RTG\BlogBundle\Entity\NewsComment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comment
     *
     * @param \RTG\BlogBundle\Entity\NewsComment $comment
     */
    public function removeComment(\RTG\BlogBundle\Entity\NewsComment $comment)
    {
        $this->articles->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set avatar
     *
     * @param \RTG\UserBundle\Entity\Avatar $avatar
     * @return User
     */
    public function setAvatar(\RTG\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \RTG\UserBundle\Entity\Avatar 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set newsletter
     *
     * @return \RTG\UserBundle\Entity\User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
        return $this;
    }


}