<?php

namespace RTG\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }
}