<?php

namespace App\Repository;

use App\Entity\Acteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acteur[]    findAll()
 * @method Acteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acteur::class);
    }

    public function acteursPlusDeToisfilm(){
        $qb =$this->createQueryBuilder('a');
         return $qb->select('a.nomPrenom')
                    ->innerJoin('a.film', 'f')
                    ->groupBy('a.id')
                    ->having($qb->expr()->gte($qb->expr()->count('f.id'),3)) 
                    ->getQuery()
                    ->getResult();
    }
    public function acteursMinimimFilms($acteur){
        $qb = $this->createQueryBuilder('a');
                    $qb= $qb->select('g.nom, count(g.nom)')
                    ->innerJoin('a.film', 'f')
                    ->innerJoin('f.genre', 'g')
                    ->having('count(g.nom) > 1')
                    ->andWhere('a.nomPrenom = :acteur')
                    ->setParameter('acteur',$acteur);
                    return $qb->getQuery()
                    ->getResult();
    }

    public function dureeEnMinFilms($acteur){
        return $this->createQueryBuilder('a')
                    ->select('sum(f.duree) as dureeTotal')
                    ->innerJoin('a.film', 'f')
                    ->andWhere('a.nomPrenom = :acteur')
                    ->setParameter('acteur',$acteur)
                    ->getQuery()
                    ->getResult();
    }

    public function filmsPourActeurchro(){
        return $this->createQueryBuilder('a')
                    ->select('a.nomPrenom,f.titre,f.dateSortie')
                    ->innerJoin('a.film', 'f')
                    ->groupBy('a.nomPrenom')
                    ->orderBy('f.dateSortie, a.nomPrenom')
                    ->getQuery()->getResult();
    }

    public function genresPourActeur(){
        return $this->createQueryBuilder('a')
                    ->select('distinct a.nomPrenom, g.nom')
                    ->innerJoin('a.film', 'f')
                    ->innerJoin('f.genre', 'g')
                    ->orderBy('a.nomPrenom,g.nom')
                    ->getQuery()->getResult();
    }
    // /**
    //  * @return Acteur[] Returns an array of Acteur objects
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
    public function findOneBySomeField($value): ?Acteur
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
