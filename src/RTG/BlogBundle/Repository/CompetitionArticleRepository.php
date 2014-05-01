<?php

namespace RTG\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompetitionArticleRepository
 */
class CompetitionArticleRepository extends EntityRepository
{
    public function getLatestArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a')
                   ->addOrderBy('a.created', 'DESC');
        
        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }
}