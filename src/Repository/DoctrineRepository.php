<?php

namespace App\Repository;

use App\Entity\Doctrine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Doctrine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctrine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctrine[]    findAll()
 * @method Doctrine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctrine::class);
    }

    // /**
    //  * @return Doctrine[] Returns an array of Doctrine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Doctrine
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
