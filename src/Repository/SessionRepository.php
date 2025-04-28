<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function countActiveSessions(): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.isActive = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalMessagesEnvoyes(): int
    {
        return $this->createQueryBuilder('s')
            ->select('SUM(s.messagesEnvoyes)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalMessagesRecus(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('SUM(s.messagesRecus)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
