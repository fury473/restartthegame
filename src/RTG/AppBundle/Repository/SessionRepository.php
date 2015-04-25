<?php

namespace RTG\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use RTG\UserBundle\Entity\User;

/**
 * SessionRepository
 */
class SessionRepository extends EntityRepository
{

    public function findAll()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }

    /**
     * @param string start
     * @param string end
     * @param User $user
     */
    public function getSessionEvents($start, $end, User $user = null)
    {
        $qb = $this->createQueryBuilder('s')->select('s');
        if ($start !== null) {
            $qb->andWhere('s.startAt >= :start')->setParameter('start', $start);
        }
        if ($end !== null) {
            $qb->andWhere('s.endAt <= :end')->setParameter('end', $end);
        }
        if ($user !== null) {
            $qb->andWhere('s.user = :user')->setParameter('user', $user);
        }

        $sessions = $qb->getQuery()->getResult();
        $events = array();
        foreach ($sessions as $session) {
            $events[] = $session->toEvent();
        }
        return $events;
    }

}
