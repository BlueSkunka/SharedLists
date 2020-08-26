<?php

namespace App\Repository;

use App\Entity\ListGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListGroup[]    findAll()
 * @method ListGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListGroup::class);
    }

    // /**
    //  * @return ListGroup[] Returns an array of ListGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListGroup
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
