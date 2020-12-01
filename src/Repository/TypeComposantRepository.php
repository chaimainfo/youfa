<?php

namespace App\Repository;

use App\Entity\TypeComposant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeComposant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeComposant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeComposant[]    findAll()
 * @method TypeComposant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeComposantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeComposant::class);
    }

    // /**
    //  * @return TypeComposant[] Returns an array of TypeComposant objects
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
    public function findOneBySomeField($value): ?TypeComposant
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
