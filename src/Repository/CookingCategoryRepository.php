<?php

namespace App\Repository;

use App\Entity\CookingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CookingCategory>
 *
 * @method CookingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CookingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CookingCategory[]    findAll()
 * @method CookingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CookingCategory::class);
    }

//    /**
//     * @return CookingCategory[] Returns an array of CookingCategory objects
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

//    public function findOneBySomeField($value): ?CookingCategory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
