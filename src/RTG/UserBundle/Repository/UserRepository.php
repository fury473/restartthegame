<?php

namespace RTG\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{

    public function getSuscribedToNewsletter()
    {
        return $this->createQueryBuilder('u')
                        ->select('u')
                        ->where('u.newsletter = true')
                        ->getQuery()
                        ->getResult();
    }

}
