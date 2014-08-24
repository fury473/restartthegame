<?php

namespace RTG\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompetitionArticleRepository
 */
class CompetitionArticleRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('created' => 'DESC'));
    }
    
    public function getLatestArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->addOrderBy('c.date', 'DESC');
        
        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }
}