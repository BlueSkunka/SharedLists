<?php

namespace App\Repository;

use App\Entity\NoticeClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoticeClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoticeClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoticeClass[]    findAll()
 * @method NoticeClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticeClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoticeClass::class);
    }

    // /**
    //  * @return NoticeClass[] Returns an array of NoticeClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoticeClass
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
