<?php

namespace App\Repository;

use App\Entity\InventoryRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventoryRoom>
 *
 * @method InventoryRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryRoom[]    findAll()
 * @method InventoryRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryRoom::class);
    }

//    /**
//     * @return InventoryRoom[] Returns an array of InventoryRoom objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InventoryRoom
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
