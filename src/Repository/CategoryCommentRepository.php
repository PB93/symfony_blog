<?php

namespace App\Repository;

use App\Entity\CategoryComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategoryComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryComment[]    findAll()
 * @method CategoryComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryComment::class);
    }

    // /**
    //  * @return CategoryComment[] Returns an array of CategoryComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryComment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
