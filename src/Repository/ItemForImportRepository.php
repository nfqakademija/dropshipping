<?php

namespace App\Repository;

use App\Entity\ItemForImport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemForImport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemForImport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemForImport[]    findAll()
 * @method ItemForImport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemForImportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemForImport::class);
    }

    // /**
    //  * @return ItemForImport[] Returns an array of ItemForImport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemForImport
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
