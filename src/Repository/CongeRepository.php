<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Conge>
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    public function findPaginated(int $page = 1, int $limit=10) : Paginator {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

//  Select les demandes de congé en attente pour affichage sur le tableau de bord
    public function findCongesEnAttente(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', 'en attente')
            ->orderBy('c.debut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countCongesEnAttente(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.status = :status')
            ->setParameter('status', 'en attente')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }
}
