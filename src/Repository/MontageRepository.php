<?php

namespace App\Repository;

use App\Entity\Montage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Montage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Montage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Montage[]    findAll()
 * @method Montage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MontageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Montage::class);
    }

    // /**
    //  * @return Montage[] Returns an array of Montage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Montage
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
