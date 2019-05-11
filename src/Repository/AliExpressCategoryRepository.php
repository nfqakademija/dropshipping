<?php

namespace App\Repository;

use App\Entity\AliExpressCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AliExpressCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AliExpressCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AliExpressCategory[]    findAll()
 * @method AliExpressCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AliExpressCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AliExpressCategory::class);
    }

    // /**
    //  * @return AliExpressCategory[] Returns an array of AliExpressCategory objects
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
    public function findOneBySomeField($value): ?AliExpressCategory
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
