<?php

namespace App\Repository;

use App\Entity\DroitAccee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DroitAccee|null find($id, $lockMode = null, $lockVersion = null)
 * @method DroitAccee|null findOneBy(array $criteria, array $orderBy = null)
 * @method DroitAccee[]    findAll()
 * @method DroitAccee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DroitAcceeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DroitAccee::class);
    }

    // /**
    //  * @return DroitAccee[] Returns an array of DroitAccee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DroitAccee
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
