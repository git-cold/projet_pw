<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visite>
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    //    /**
    //     * @return Visite[] Returns an array of Visite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Visite
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByEtudiantFiltered(int $etudiantId, ?string $statut, ?string $tri) {
        $qb = $this->createQueryBuilder('v')
            ->andWhere('v.etudiant = :id')
            ->setParameter('id', $etudiantId);

        // Filtre statut
        if ($statut && $statut !== 'toutes') {
            $qb->andWhere('v.statut = :statut')
            ->setParameter('statut', $statut);
        }

        // Tri
        if ($tri === 'asc') {
            $qb->orderBy('v.date', 'ASC');
        } elseif ($tri === 'desc') {
            $qb->orderBy('v.date', 'DESC');
        } else {
            $qb->orderBy('v.date', 'ASC'); // par défaut
        }

        return $qb->getQuery()->getResult();
    }

}
