<?php

namespace App\Repository;

use App\Entity\Tableat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tableat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tableat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tableat[]    findAll()
 * @method Tableat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tableat::class);
    }

    // /**
    //  * @return Tableat[] Returns an array of Tableat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tableat
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
