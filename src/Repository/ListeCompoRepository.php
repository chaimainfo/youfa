<?php

namespace App\Repository;

use App\Entity\ListeCompo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListeCompo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeCompo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeCompo[]    findAll()
 * @method ListeCompo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeCompoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListeCompo::class);
    }

    // /**
    //  * @return ListeCompo[] Returns an array of ListeCompo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListeCompo
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
