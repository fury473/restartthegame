<?php

namespace RTG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RTG\AppBundle\Entity\Image;

/**
 * @ORM\Entity
 * @ORM\Table("avatar")
 */
class Avatar extends Image
{
    public function __toString()
    {
        return (string) $this->id;
    }
    
    protected function getUploadDir()
    {
        return 'uploads/user/img/article';
    }
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}