<?php

namespace App\Repository;

use App\Entity\ShipReplacementRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShipReplacementRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipReplacementRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipReplacementRequest[]    findAll()
 * @method ShipReplacementRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipReplacementRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipReplacementRequest::class);
    }

    // /**
    //  * @return ShipReplacementRequest[] Returns an array of ShipReplacementRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShipReplacementRequest
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
