<?php

namespace RTG\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StreamRepository
 */
class StreamRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }
}