<?php

namespace App\Repository;

use App\Entity\EbayCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EbayCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method EbayCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method EbayCountry[]    findAll()
 * @method EbayCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EbayCountryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EbayCountry::class);
    }


    // /**
    //  * @return EbayCountry[] Returns an array of EbayCountry objects
    //  */

    public function getCountries()
    {
        return $this->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?EbayCountry
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
