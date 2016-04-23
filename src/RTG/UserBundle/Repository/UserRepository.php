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
                        ->select('u')
                        ->where('u.roles LIKE :role')
                        ->andWhere('u.twitchAccessToken is not null')
                        ->setParameter('role', '%ROLE_STREAMER%')
                        ->orderBy('u.username')
                        ->getQuery()
                        ->getResult();
    }
    
    public function findStaffMembers()
    {
        return $this->createQueryBuilder('u')
                        ->select('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_STAFF%')
                        ->orderBy('u.username')
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
