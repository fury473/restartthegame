<?php

namespace RTG\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RTG\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="RTG\AppBundle\Repository\ChatClientRepository")
 * @ORM\Table(name="chat_client")
 */
class ChatClient
{

    /**
     * @param integer $resource_id
     */
    public function __construct($resource_id)
    {
        $this->connectedAt = new DateTime();
        $this->id = $resource_id;
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
    public function getConnectedAt()
    {
        return $this->connectedAt;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param DateTime $connected_at
     * @return ChatClient
     */
    public function setConnectedAt(DateTime $connected_at)
    {
        $this->connectedAt = $connected_at;
        return $this;
    }

    /**
     * @param string $status
     * @return ChatClient
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param User $user
     * @return ChatClient
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'connectedAt' => $this->connectedAt,
            'status' => $this->status,
            'user' => $this->user->getId()
        );
    }
    
    /**
     * @ORM\Column(name="connected_at", type="datetime")
     * @var DateTime
     */
    protected $connectedAt;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="status", type="string")
     * @var string
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="RTG\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @var User
     */
    protected $user;

}
