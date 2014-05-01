<?php

namespace RTG\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NewsArticleRepository
 */
class NewsArticleRepository extends EntityRepository
{
    public function getLatestArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a, c')
                   ->leftJoin('a.comments', 'c')
                   ->addOrderBy('a.created', 'DESC');
        
        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getFeaturedArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a, c')
                   ->leftJoin('a.comments', 'c')
                   ->addOrderBy('a.created', 'DESC')
                   ->where('a.featured = true');
        
        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }
}