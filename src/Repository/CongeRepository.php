<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conge>
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
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
