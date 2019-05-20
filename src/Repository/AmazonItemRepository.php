<?php

namespace App\Repository;

use App\Entity\AmazonItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AmazonItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmazonItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmazonItem[]    findAll()
 * @method AmazonItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmazonItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AmazonItem::class);
    }

    // /**
    //  * @return AmazonItem[] Returns an array of AmazonItem objects
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
    public function findOneBySomeField($value): ?AmazonItem
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
