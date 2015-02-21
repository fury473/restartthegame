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
