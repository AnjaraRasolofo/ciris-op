<?php

namespace App\Repository;

use App\Entity\Operateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Operateur>
 */
class OperateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operateur::class);
    }

    public function findPaginated(int $page = 1, int $limit=10) : Paginator {
        $query = $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findActifsPaginated(int $page = 1, int $limit=10): Paginator {
       $query = $this->createQueryBuilder('o')
            ->where('o.status = :status')
            ->setParameter('status', 'actif') 
            ->orderBy('o.nom', 'ASC')
             ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    public function findPaginatedByName(string $search = '', int $page = 1, int $limit = 10): Paginator
    {
        $qb = $this->createQueryBuilder('o');

        if (!empty($search)) {
            $qb->where('o.nom LIKE :search OR o.prenom LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        $qb->orderBy('o.nom', 'ASC')
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    public function resetActifStatus(): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.status', ':status')
            ->setParameter('status', 'inactif')
            ->getQuery()
            ->execute();
    }

    public function activateRandomOperators(int $count): void
    {
        $operateurs = $this->createQueryBuilder('o')
            ->getQuery()
            ->getResult();

        shuffle($operateurs);

        $selected = array_slice($operateurs, 0, $count);

        foreach ($selected as $op) {
            $op->setIsActif(true);
        }

        $this->_em->flush();
    }

}
