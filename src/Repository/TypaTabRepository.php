<?php

namespace App\Repository;

use App\Entity\TypaTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypaTab|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypaTab|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypaTab[]    findAll()
 * @method TypaTab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypaTabRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypaTab::class);
    }

    // /**
    //  * @return TypaTab[] Returns an array of TypaTab objects
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
    public function findOneBySomeField($value): ?TypaTab
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
