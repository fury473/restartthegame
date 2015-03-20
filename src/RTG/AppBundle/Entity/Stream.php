<?php

namespace RTG\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RTG\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="RTG\AppBundle\Repository\StreamRepository")
 * @ORM\Table(name="stream", uniqueConstraints={
 * @ORM\UniqueConstraint(name="search_idx", columns={"slug"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Stream
{

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
    
    /**
     * @return DateTime
     */
    function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    function getEndAt()
    {
        return $this->endAt;
    }

    /** 
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return DateTime
     */
    function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @return string
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * @return User
     */
    function getUser()
    {
        return $this->user;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function refreshSlug()
    {
        $this->setSlug($this->title);
    }

    /**
     * @param DateTime $createdAt
     * @return Stream
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime $endAt
     * @return Stream
     */
    public function setEndAt(DateTime $endAt)
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * @param integer $id
     * @return Stream
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $slug
     * @return Stream
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param DateTime $startAt
     * @return Stream
     */
    public function setStartAt(DateTime $startAt)
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * @param string $title
     * @return Stream
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param User $user
     * @return Stream
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @Assert\DateTime()
     * @ORM\Column(name="created_at", type="datetime")
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @Assert\DateTime()
     * @ORM\Column(name="end_at", type="datetime", nullable=true)
     * @var DateTime
     */
    protected $endAt;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="slug", type="string")
     * @var string
     */
    protected $slug;

    /**
     * @Assert\DateTime()
     * @Assert\NotNull()
     * @ORM\Column(name="start_at", type="datetime")
     * @var DateTime
     */
    protected $startAt;
    
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=120)
     * @var string
     */
    protected $title;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="RTG\UserBundle\Entity\User", inversedBy="streams")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     * @var User
     */
    protected $user;

}
