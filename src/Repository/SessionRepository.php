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
            ->getSingleScalarResult() ?? 0;
    }

    public function getTotalMessagesEnvoyes(): int
    {
        return $this->createQueryBuilder('s')
            ->select('SUM(s.messagesEnvoyes)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function getTotalMessagesRecus(): int
    {
        return (int) $this->createQueryBuilder('s')
            ->select('SUM(s.messagesRecus)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function getStatsOfToday(): array
{
    $today = new \DateTimeImmutable('today');
    $tomorrow = $today->modify('+1 day');

    return $this->createQueryBuilder('s')
        ->select('COUNT(s.id) AS totalSessions')
        ->addSelect('SUM(s.messagesEnvoyes) AS totalEnvoyes')
        ->addSelect('SUM(s.messagesRecus) AS totalRecus')
        ->where('s.debut >= :today')
        ->andWhere('s.debut < :tomorrow')
        ->setParameter('today', $today)
        ->setParameter('tomorrow', $tomorrow)
        ->getQuery()
        ->getOneOrNullResult();
}
}
