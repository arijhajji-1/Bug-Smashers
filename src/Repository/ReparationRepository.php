<?php

namespace App\Repository;

use App\Data\SearchDataReparation;
use App\Entity\Reparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reparation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reparation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reparation[]    findAll()
 * @method Reparation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparation::class);
    }

    // /**
    //  * @return Reparation[] Returns an array of Reparation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reparation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * recupere les annonces en lien avec recherche
     * @return Reparation[]
     */
    public function findSearch(SearchDataReparation $search):array
    {
        $query= $this
            ->createQueryBuilder('x');


        if (!empty($search->y))
        {
            $query=$query
                ->andWhere('x.email LIKE :y')
                ->setParameter('y',"{$search->y}%");
        }

        return $query->getQuery()->getResult();
    }
}
