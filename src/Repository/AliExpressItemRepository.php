<?php

namespace App\Repository;

use App\Entity\AliExpressItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AliExpressItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method AliExpressItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method AliExpressItem[]    findAll()
 * @method AliExpressItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AliExpressItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AliExpressItem::class);
    }

    // /**
    //  * @return AliExpressItem[] Returns an array of AliExpressItem objects
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
    public function findOneBySomeField($value): ?AliExpressItem
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
