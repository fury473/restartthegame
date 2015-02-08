<?php

namespace RTG\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{

    public function findStreamers()
    {
        return $this->createQueryBuilder('u')
                        ->select('u.id', 'u.username')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_STREAMER%')
                        ->getQuery()
                        ->getResult();
    }

    public function getSuscribedToNewsletter()
    {
        return $this->createQueryBuilder('u')
                        ->select('u')
                        ->where('u.newsletter = true')
                        ->getQuery()
                        ->getResult();
    }

}
