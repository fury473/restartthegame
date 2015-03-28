<?php

namespace RTG\AppBundle\Model;

use RTG\UserBundle\Entity\User;

abstract class Channel
{
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $url
     * @return Channel
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param User $user
     * @return Channel
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @var string
     */
    private $url;

    /**
     * @var User
     */
    private $user;

}
