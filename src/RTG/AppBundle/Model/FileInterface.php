<?php

namespace RTG\AppBundle\Model;

interface FileInterface
{

    public function getWebPath();

    public function getAbsolutePath();

    public function preUpload();
    
    public function upload();
    
    public function storeFilenameForRemove();

    public function removeUpload();
}