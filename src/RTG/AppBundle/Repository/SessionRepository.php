<?php

namespace RTG\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SessionRepository
 */
class SessionRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }
}