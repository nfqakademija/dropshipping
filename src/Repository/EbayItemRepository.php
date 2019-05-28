<?php

namespace App\Repository;

use App\Entity\EbayItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EbayItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method EbayItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method EbayItem[]    findAll()
 * @method EbayItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EbayItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EbayItem::class);
    }

    // /**
    //  * @return EbayItem[] Returns an array of EbayItem objects
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
    public function findOneBySomeField($value): ?EbayItem
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
