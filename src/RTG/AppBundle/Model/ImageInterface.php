<?php

namespace RTG\AppBundle\Model;

interface ImageInterface
{
    public function getAbsoluteThumbPath($width, $height);
}