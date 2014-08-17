<?php

namespace RTG\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use RTG\BlogBundle\Entity\Category;

/**
 * NewsArticleRepository
 */
class NewsArticleRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('created' => 'DESC'));
    }
    
    public function findByCategoryOrNull(Category $category)
    {
        return $this->createQueryBuilder('a')
                    ->select('a')
                    ->where('a.category IS NULL')
                    ->orWhere('a.category = :category')
                    ->setParameter('category', $category)
                    ->orderBy('a.title', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    
    public function getLatestArticles(Category $category = null, $limit = null)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a, c')
                   ->leftJoin('a.comments', 'c')
                   ->addOrderBy('a.created', 'DESC');
        
        if ($category != null) {
            $qb->where('a.category = :category')
               ->setParameter('category', $category);
        }
        
        if ($limit != null) {
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