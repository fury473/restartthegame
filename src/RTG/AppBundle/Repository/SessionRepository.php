<?php

namespace RTG\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
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
        if ($start == null or $end == null or $start >= $end) {
            throw new InvalidArgumentException();
        }
        $qb = $this->createQueryBuilder('s')
                ->select('s')
                ->where(':start <= s.endAt AND s.startAt <= :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);
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
