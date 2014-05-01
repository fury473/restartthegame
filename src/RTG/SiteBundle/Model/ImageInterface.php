<?php

namespace RTG\SiteBundle\Model;

interface ImageInterface
{
    public function getWebThumbPath();

    public function getAbsoluteThumbPath();
}