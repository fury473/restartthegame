<?php

namespace RTG\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RTG\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="RTG\AppBundle\Repository\SessionRepository")
 * @ORM\Table(name="session")
 */
class Session
{
    
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
     * @param DateTime $createdAt
     * @return Session
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime $endAt
     * @return Session
     */
    public function setEndAt(DateTime $endAt)
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * @param integer $id
     * @return Session
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param DateTime $startAt
     * @return Session
     */
    public function setStartAt(DateTime $startAt)
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * @param string $title
     * @return Session
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param User $user
     * @return Session
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @var DateTime
     */
    protected $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="change", field={"startAt", "endAt"})
     * @ORM\Column(name="$date_changed", type="datetime")
     * @var DateTime
     */
    protected $dateChanged;

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
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="RTG\UserBundle\Entity\User", inversedBy="sessions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     * @var User
     */
    protected $user;

}
