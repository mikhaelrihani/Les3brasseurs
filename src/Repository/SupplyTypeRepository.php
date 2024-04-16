<?php

namespace App\Repository;

use App\Entity\SupplyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupplyType>
 *
 * @method SupplyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyType[]    findAll()
 * @method SupplyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyType::class);
    }

//    /**
//     * @return SupplyType[] Returns an array of SupplyType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SupplyType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
