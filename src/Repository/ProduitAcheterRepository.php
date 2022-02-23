<?php

namespace App\Repository;
use App\Data\SearchData;
use App\Entity\ProduitAcheter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProduitAcheter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitAcheter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitAcheter[]    findAll()
 * @method ProduitAcheter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitAcheterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitAcheter::class);
    }

    // /**
    //  * @return ProduitAcheter[] Returns an array of ProduitAcheter objects
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
    /**
     * recupere les annonces en lien avec recherche
     * @return Produit[]
     */
    public function findSearch(SearchData $search):array
    {
        $query= $this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.category','c');
        if (!empty($search->q))
        {
            $query=$query
                ->andWhere('p.nom LIKE :q OR p.description LIKE :q OR p.marque LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }
        if (!empty($search->category))
        {
            $query=$query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category',$search->category);
        }
        return $query->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?ProduitAcheter
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
