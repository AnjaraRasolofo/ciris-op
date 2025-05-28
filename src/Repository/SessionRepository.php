<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\Operateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findPaginated(int $page = 1, int $limit=10) : Paginator {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
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

    public function findActifsPaginated(): array {

        return $this->createQueryBuilder('s')
            ->innerJoin('s.operateur', 'o')
            ->addSelect('o')
            ->where('s.actif = :val')
            ->setParameter('val', true)
            ->orderBy('s.debut', 'DESC')
            ->getQuery()
            ->getResult();

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
