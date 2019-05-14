<?php

namespace App\Repository;

use App\Entity\Ebay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ebay|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ebay|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ebay[]    findAll()
 * @method Ebay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EbayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ebay::class);
    }

    // /**
    //  * @return Ebay[] Returns an array of Ebay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ebay
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
