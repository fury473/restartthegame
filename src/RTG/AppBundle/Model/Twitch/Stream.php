<?php

namespace RTG\AppBundle\Model\Twitch;

use DateTime;

class Stream
{

    /**
     * @param string $json
     */
    public function __construct($json)
    {
        $data = json_decode($json);
        $this->setCreatedAt(new DateTime($data->created_at));
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @var DateTime 
     */
    private $createdAt;

}
