<?php

namespace RTG\AppBundle\Model;

interface ImageInterface
{
    public function getWebThumbPath();

    public function getAbsoluteThumbPath();
}