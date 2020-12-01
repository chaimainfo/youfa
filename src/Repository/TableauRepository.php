<?php

namespace App\Repository;

use App\Entity\Tableau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tableau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tableau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tableau[]    findAll()
 * @method Tableau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tableau::class);
    }

    // /**
    //  * @return Tableau[] Returns an array of Tableau objects
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
    public function findOneBySomeField($value): ?Tableau
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
