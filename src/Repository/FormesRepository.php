<?php

namespace App\Repository;

use App\Entity\Formes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formes[]    findAll()
 * @method Formes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formes::class);
    }

    // /**
    //  * @return Formes[] Returns an array of Formes objects
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
    public function findOneBySomeField($value): ?Formes
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
