<?php

namespace App\Repository;

use App\Entity\OrdersProductsRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrdersProductsRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersProductsRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersProductsRelation[]    findAll()
 * @method OrdersProductsRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersProductsRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersProductsRelation::class);
    }

    // /**
    //  * @return OrdersProductsRelation[] Returns an array of OrdersProductsRelation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrdersProductsRelation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
