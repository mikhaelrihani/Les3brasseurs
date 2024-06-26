<?php

namespace App\Repository;

use App\Entity\Mime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mime>
 *
 * @method Mime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mime[]    findAll()
 * @method Mime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mime::class);
    }

//    /**
//     * @return Mime[] Returns an array of Mime objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mime
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
