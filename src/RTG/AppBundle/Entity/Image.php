<?php

namespace RTG\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RTG\AppBundle\Model\ImageInterface;

/**
 * @ORM\MappedSuperclass
 */
abstract class Image extends File implements ImageInterface
{

    public function getAbsoluteThumbPath($filter)
    {
        return null === $this->path ? null : $this->getUploadThumbRootDir($filter) . '/' . $this->path;
    }

    public static function getUploadDir()
    {
        return 'uploads/img';
    }

    protected function getUploadThumbDir($filter)
    {
        return 'media/cache/' . $filter . '/' . $this->getUploadDir();
    }

    protected function getUploadThumbRootDir($filter)
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadThumbDir($filter);
    }

}
