<?php

namespace RTG\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RTG\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="RTG\AppBundle\Repository\ChatMessageRepository")
 * @ORM\Table(name="chat_message")
 */
class ChatMessage
{

    public function __construct()
    {
        $this->time = new DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $text
     * @return ChatMessage
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param DateTime $time
     * @return ChatMessage
     */
    public function setTime(DateTime $time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @param User $user
     * @return ChatMessage
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="text", type="string")
     * @var string
     */
    protected $text;

    /**
     * @ORM\Column(name="time", type="datetime")
     * @var DateTime
     */
    protected $time;

    /**
     * @ORM\ManyToOne(targetEntity="RTG\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    protected $user;

}
