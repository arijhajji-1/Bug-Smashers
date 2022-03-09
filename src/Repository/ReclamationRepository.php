<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */

    public function searchReclaim($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.categorie = :cat')
            ->setParameter('cat', $value['categorie'])
            ->andWhere('r.date = :date')
            ->setParameter('date', $value['date'])
            ->orderBy('r.code', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * Recherche les annonces en fonction du formulaire
     * @return void
     */
    public function search($mots){
        $query = $this->createQueryBuilder('a');
        $query->where('a.statut = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.description, a.categorie) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }

        return $query->getQuery()->getResult();
    }

    /**
     *fonction de statistique: nombre de reclamations par catégorie
     */
    public function getNb(){

        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id) AS nbr_rec, r.categorie AS categ')
            ->groupBy('categ');

        return $qb->getQuery()
            ->getResult();

    }

    /**
     * nombre de reclamations par jour
     */

    public function countday()
    {

        $qb = $this->createQueryBuilder('r')
            ->select('SUBSTRING(r.date,1,7) AS date, COUNT(r) AS count')
            ->groupBy('date');

        return $qb->getQuery()
            ->getResult();

    }











    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
