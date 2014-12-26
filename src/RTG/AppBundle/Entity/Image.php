<?php

namespace RTG\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RTG\AppBundle\Model\ImageInterface;

/**
 * @ORM\MappedSuperclass
 */
abstract class Image extends File implements ImageInterface
{

    public function getAbsoluteThumbPath($width, $height)
    {
        return null === $this->path ? null : $this->getUploadThumbRootDir($width, $height) . '/' . $this->path;
    }

    protected function getUploadDir()
    {
        return 'uploads/img';
    }

    protected function getUploadThumbDir($width, $height)
    {
        return 'media/cache/tb-' . $width . 'x' . $height . '/' . $this->getUploadDir();
    }

    protected function getUploadThumbRootDir($width, $height)
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadThumbDir($width, $height);
    }

}
