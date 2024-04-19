<?php

namespace App\Repository;

use App\Entity\DishPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DishPicture>
 *
 * @method DishPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method DishPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method DishPicture[]    findAll()
 * @method DishPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DishPicture::class);
    }

//    /**
//     * @return DishPicture[] Returns an array of DishPicture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DishPicture
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
