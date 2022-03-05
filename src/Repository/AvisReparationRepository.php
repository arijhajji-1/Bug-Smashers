<?php

namespace App\Repository;

use App\Entity\AvisReparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisReparation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisReparation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisReparation[]    findAll()
 * @method AvisReparation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisReparationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisReparation::class);
    }

    // /**
    //  * @return AvisReparation[] Returns an array of AvisReparation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisReparation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
