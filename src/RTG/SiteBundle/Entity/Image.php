<?php

namespace RTG\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RTG\SiteBundle\Model\ImageInterface;

/**
 * @ORM\MappedSuperclass
 */
abstract class Image extends File implements ImageInterface
{
    public function getAbsoluteThumbPath() {
        return null === $this->path ? null : $this->getUploadRootDir().'/mini/'.$this->path;
    }

    public function getWebThumbPath() {
        return null === $this->path ? null : $this->getUploadDir().'/mini/'.$this->path;
    }

    protected function getUploadDir() {
        return 'uploads/img';
    }
}