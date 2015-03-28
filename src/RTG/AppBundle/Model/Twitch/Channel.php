<?php

namespace RTG\AppBundle\Model\Twitch;

use RTG\AppBundle\Model\Channel as BaseChannel;

class Channel extends BaseChannel
{

    /**
     * @param string $json
     */
    public function __construct($json)
    {
        $data = json_decode($json);
        $this->setDisplayName($data->display_name);
        $this->setGame($data->game);
        $this->setLogoUrl($data->logo);
        $this->setName($data->name);
        $this->setStatus($data->status);
        $this->setUrl($data->url);
    }

    /**
     * @return Channel
     */
    public function getBroadcastedChannel()
    {
        if ($this->getHostedChannel() !== null) {
            return $this->getHostedChannel();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    /**
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return Channel
     */
    public function getHostedChannel()
    {
        return $this->hostedChannel;
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $displayName
     * @return Channel
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }
    
    /**
     * @param string $game
     * @return Channel
     */
    public function setGame($game)
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @param Channel $hostedChannel
     * @return Channel
     */
    public function setHostedChannel($hostedChannel)
    {
        $this->hostedChannel = $hostedChannel;
        return $this;
    }

    /**
     * @param string $logoUrl
     * @return Channel
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
        return $this;
    }

    /**
     * @param string $name
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @param string $status
     * @return Channel
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @var string
     */
    private $displayName;
    
    /**
     * @var string
     */
    private $game;

    /**
     * @var Channel
     */
    private $hostedChannel;

    /**
     * @var string
     */
    private $logoUrl;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $status;

}
