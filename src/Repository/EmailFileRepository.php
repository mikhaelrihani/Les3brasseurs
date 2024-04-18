<?php

namespace App\Repository;

use App\Entity\EmailFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailFile>
 *
 * @method EmailFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailFile[]    findAll()
 * @method EmailFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailFile::class);
    }

//    /**
//     * @return EmailFile[] Returns an array of EmailFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmailFile
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
