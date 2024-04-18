<?php

namespace App\Repository;

use App\Entity\CookingSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CookingSheet>
 *
 * @method CookingSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CookingSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CookingSheet[]    findAll()
 * @method CookingSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookingSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CookingSheet::class);
    }

//    /**
//     * @return CookingSheet[] Returns an array of CookingSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CookingSheet
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
