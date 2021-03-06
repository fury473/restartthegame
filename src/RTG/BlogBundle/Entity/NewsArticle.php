<?php

namespace RTG\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RTG\AppBundle\Util\String;

/**
 * @ORM\Entity(repositoryClass="RTG\BlogBundle\Repository\NewsArticleRepository")
 * @ORM\Table(name="news_article", uniqueConstraints={
 * @ORM\UniqueConstraint(name="search_idx", columns={"slug"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class NewsArticle
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="RTG\BlogBundle\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $featured;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", name="catch_phrase")
     */
    protected $catchPhrase;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="RTG\UserBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;

    /**
     * @ORM\OneToOne(targetEntity="RTG\BlogBundle\Entity\ImageArticle", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsComment", mappedBy="article", cascade={"remove"})
     */
    protected $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->featured = true;

        $this->setCreated(new DateTime());
        $this->setUpdated(new DateTime());
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new DateTime());
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
     * Set category
     *
     * @param \RTG\BlogBundle\Entity\Cateogry $category
     * @return Article
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Get category
     *
     * @return \RTG\BlogBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set featured
     *
     * @param boolean $featured
     * @return Article
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean 
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        $this->setSlug($this->title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set catchPhrase
     *
     * @param string $catch_phrase
     * @return Article
     */
    public function setCatchPhrase($catch_phrase)
    {
        $this->catchPhrase = $catch_phrase;

        return $this;
    }

    /**
     * Get catchPhrase
     *
     * @return string 
     */
    public function getCatchPhrase()
    {
        return $this->catchPhrase;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = String::slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image
     *
     * @param \RTG\BlogBundle\Entity\ImageArticle $image
     * @return NewsArticle
     */
    public function setImage(\RTG\BlogBundle\Entity\ImageArticle $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \RTG\BlogBundle\Entity\ImageArticle 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set author
     *
     * @param \RTG\UserBundle\Entity\User $author
     * @return NewsArticle
     */
    public function setAuthor(\RTG\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \RTG\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Article
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage($length = null)
    {
        if (false === is_null($length) && $length > 0)
            return substr($this->message, 0, $length);
        else
            return $this->message;
    }

    /**
     * Set created
     *
     * @param DateTime $created
     * @return Article
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param DateTime $updated
     * @return Article
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add comments
     *
     * @param NewsComment $comments
     * @return NewsArticle
     */
    public function addComment(NewsComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param NewsComment $comments
     */
    public function removeComment(NewsComment $comments)
    {
        $this->comments->removeElement($comments);
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

}