<?php

namespace App\Repository;

use App\Entity\TypeTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeTab|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTab|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTab[]    findAll()
 * @method TypeTab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeTabRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeTab::class);
    }

    // /**
    //  * @return TypeTab[] Returns an array of TypeTab objects
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
    public function findOneBySomeField($value): ?TypeTab
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
