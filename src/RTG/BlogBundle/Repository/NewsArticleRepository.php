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
        $results = $this->createQueryBuilder('a')
                ->select('a, c')
                ->leftJoin('a.comments', 'c')
                ->addOrderBy('a.created', 'DESC')
                ->where('a.featured = true')
                ->getQuery()
                ->getResult();

        if ($limit != null) {
            $results = array_slice($results, 0, $limit);
        }

        return $results;
    }

    public function getRecentArticles(Category $category = null, $limit = null)
    {
        $qb = $this->createQueryBuilder('a')
                ->select('a')
                ->where('a.created > :date')
                ->setParameter('date', new \DateTime('-1 month'))
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

    public function search($query, $limit = null)
    {
        $qb = $this->createQueryBuilder('a');
        $qb = $qb->select('a')
                ->where($qb->expr()->like('a.title', ':query'))
                ->setParameter('query', '%' . $query . '%')
                ->addOrderBy('a.created', 'DESC');

        if ($limit != null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                        ->getResult();
    }

    public function searchCount($query)
    {
        $qb = $this->createQueryBuilder('a');
        $qb = $qb->select('count(a)')
                ->where($qb->expr()->like('a.title', ':query'))
                ->setParameter('query', '%' . $query . '%')
                ->addOrderBy('a.created', 'DESC');

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

}
