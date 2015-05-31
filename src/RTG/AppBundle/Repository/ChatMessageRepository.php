<?php

namespace RTG\AppBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use RTG\UserBundle\Entity\Avatar;

/**
 * ChatMessageRepository
 */
class ChatMessageRepository extends EntityRepository
{

    public function clear()
    {
        $em = $this->getEntityManager();
        $messages = parent::findAll();
        foreach ($messages as $message) {
            $em->remove($message);
        }
        $em->flush();
    }

    /**
     * @return array
     */
    public function getLastsMessages()
    {
        $limit = new DateTime('last hour');
        return $this->createQueryBuilder('m')
                        ->select('m')
                        ->where('m.time > :limit')
                        ->andWhere('m.user is not null')
                        ->setParameter('limit', $limit)
                        ->orderBy('m.time', 'asc')
                        ->getQuery()
                        ->getResult();
    }

}
