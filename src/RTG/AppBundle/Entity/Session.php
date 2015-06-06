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
    
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->dateChanged = new DateTime();
        $this->updatedAt = new DateTime();
    }

    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /** 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return User
     */
    public function getUser()
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
    
    public function toArray()
    {
        return array(
            'created_at' => $this->createdAt,
            'date_changed' => $this->dateChanged,
            'end_at' => $this->endAt,
            'id' => $this->id,
            'start_at' => $this->startAt,
            'title' => $this->title,
            'updated_at' => $this->updatedAt,
            'user_id' => $this->user->getId(),
        );
    }
    
    public function toEvent()
    {
        return array(
            'end' => ($this->endAt != null) ? $this->endAt->format('c') : null,
            'id' => $this->id,
            'start' => $this->startAt->format('c'),
            'title' => $this->title,
            'organizer' => $this->user->getUsernameCanonical()
        );
    }

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @var DateTime
     */
    protected $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="change", field={"startAt", "endAt"})
     * @ORM\Column(name="date_changed", type="datetime")
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
