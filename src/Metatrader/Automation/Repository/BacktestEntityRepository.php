<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Repository;

use App\Metatrader\Automation\Entity\BacktestEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|BacktestEntity find($id, $lockMode = null, $lockVersion = null)
 * @method null|BacktestEntity findOneBy(array $criteria, array $orderBy = null)
 * @method BacktestEntity[]    findAll()
 * @method BacktestEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BacktestEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BacktestEntity::class);
    }

    // /**
    //  * @return BacktestEntity[] Returns an array of BacktestEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?BacktestEntity
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */
}
