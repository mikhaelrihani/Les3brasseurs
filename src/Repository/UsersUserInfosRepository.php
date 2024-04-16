<?php

namespace App\Repository;

use App\Entity\UsersUserInfos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsersUserInfos>
 *
 * @method UsersUserInfos|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersUserInfos|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersUserInfos[]    findAll()
 * @method UsersUserInfos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersUserInfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersUserInfos::class);
    }

//    /**
//     * @return UsersUserInfos[] Returns an array of UsersUserInfos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UsersUserInfos
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
