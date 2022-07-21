<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\createQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function reserch($d1,$d2){
        return $this->createQueryBuilder('q')
                    ->select('q')
                    ->Where("q.dateSortie > ?1")
                    ->setParameter(1, $d1)
                    ->andWhere("q.dateSortie < ?2")
                    ->setParameter(2, $d2)
                    ->getQuery()
                    ->getResult();


    }

    public function reserchFilmAnte($d){
        return $this->createQueryBuilder('q')
                    ->select('q')
                    ->Where("q.dateSortie < ?1")
                    ->setParameter(1, $d)
                    ->getQuery()
                    ->getResult();

    }

    //les films dans lesquels 2 acteurs donnés ont joué ensemble
    public function filmsDoncDeuxActeursEns($acteur1, $acteur2){
        return $this->createQueryBuilder('f')
                    ->innerJoin('f.acteurs', 'a')
                    ->innerJoin('f.acteurs', 'b')
                    ->Where('a.nomPrenom= :acteur1')
                    ->andWhere('b.nomPrenom= :acteur2')
                    ->setParameter('acteur1', $acteur1)
                    ->setParameter('acteur2', $acteur2)
                    ->getQuery()
                    ->getResult();
        
    }

    public function augNoteFilm(){
        return $this->createQueryBuilder('q')
                    ->update('q')
                    ->set('f.note','f.note+1')
                    ->Where('f.titre = :titre')
                    ->setParameter('titre',$titre)
                    ->getQuery()
                    ->getResult();

    }
    public function dimNoteFilm(){
        return $this->createQueryBuilder('f')
                    ->update('f')
                    ->set('f.note','f.note-1')
                    ->Where('f.titre = :titre')
                    ->setParameter('titre',$titre)
                    ->getQuery()
                    ->getResult();
    }

    public function reserchParPartie(){
        return $this->createQueryBuilder('f')
                    ->Where('f.titre like :titre')
                    ->setParameter('titre',"%".$titre."%")
                    ->getQuery()
                    ->getResult();
                }


    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
