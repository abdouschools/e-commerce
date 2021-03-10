<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }
    /** 
     *@return Articles[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'g', 'f', 'e', 'd', 'p')
            ->leftjoin('p.categorie',  'c ')
            ->leftjoin('p.couleurs',  'd ')
            ->leftjoin('p.marques',  'e')
            ->leftjoin('p.marques',  'f')
            ->leftjoin('p.style',  'g');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.prix >= :min')
                ->setParameter('min', $search->min);
        }
        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.prix <= :max')
                ->setParameter('max', $search->max);
        }
        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN  (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if (!empty($search->couleurs)) {
            $query = $query
                ->andWhere('d.id IN  (:couleurs)')
                ->setParameter('couleurs', $search->couleurs);
        }
        if (!empty($search->marques)) {
            $query = $query
                ->andWhere('e.id IN  (:marques)')
                ->setParameter('marques', $search->marques);
        }
        if (!empty($search->formes)) {
            $query = $query
                ->andWhere('f.id IN  (:formes)')
                ->setParameter('formes', $search->formes);
        }
        if (!empty($search->style)) {
            $query = $query
                ->andWhere('g.id IN  (:style)')
                ->setParameter('style', $search->style);
        }


        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Articles[] Returns an array of Articles objects
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
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function recuperation()
    {
        return $this->createQueryBuilder('a')
            //->andWhere('a.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('a.datecreation', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }

    /** 
     *@return Articles[]
     */
    public function findArray($array)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id  IN  (:array)')
            ->setParameter('array', $array);
        return $qb->getQuery()->getResult();
    }
}
