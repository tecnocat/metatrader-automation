<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Repository;

use App\Metatrader\Automation\Entity\BacktestReportEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|BacktestReportEntity find($id, $lockMode = null, $lockVersion = null)
 * @method null|BacktestReportEntity findOneBy(array $criteria, array $orderBy = null)
 * @method BacktestReportEntity[]    findAll()
 * @method BacktestReportEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BacktestReportEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BacktestReportEntity::class);
    }

    // /**
    //  * @return BacktestReportEntity[] Returns an array of BacktestReportEntity objects
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
    public function findOneBySomeField($value): ?BacktestReportEntity
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
