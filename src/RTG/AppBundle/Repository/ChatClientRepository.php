<?php

namespace RTG\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use RTG\AppBundle\Entity\ChatClient;
use RTG\UserBundle\Entity\User;

/**
 * ChatClientRepository
 */
class ChatClientRepository extends EntityRepository
{

    public function clear()
    {
        $em = $this->getEntityManager();
        $clients = parent::findAll();
        foreach ($clients as $client) {
            $em->remove($client);
        }
        $em->flush();
    }

    /**
     * @param integer $resource_id
     * @param User $user
     * @return ChatClient
     */
    public function connect($resource_id, User $user)
    {
        $em = $this->getEntityManager();

        $client = new ChatClient($resource_id);
        $client->setUser($user);
        $client->setStatus('online');
        $em->persist($client);
        $em->flush();
        return $client;
    }

    /**
     * @param ChatClient $client
     * @return string
     */
    public function disconnect(ChatClient $client)
    {
        $em = $this->getEntityManager();
        $em->remove($client);
        $em->flush();
        return $client->getUser()->getUsername();
    }

    public function findAll()
    {
        return $this->createQueryBuilder('o')
                        ->select('o.id, o.connectedAt, o.status, u.id user_id, u.username')
                        ->leftJoin('o.user', 'u')
                        ->orderBy('u.username')
                        ->getQuery()
                        ->getResult();
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isLoggedIn(User $user)
    {
        $count = $this->createQueryBuilder('o')
                ->select('count(o.id)')
                ->where('o.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
        return ($count > 0);
    }

}
