<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //find comments by video id
    public function findCommentsByVideoId($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.video_id = :val')
            ->andWhere('r.comment IS NOT NULL')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    //number of comments by video id
    public function countCommentsByVideoId($value): int
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.video_id = :val')
            ->andWhere('r.comment IS NOT NULL')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //remove comment by id and user id if user is the owner of the comment or admin and comment is not null
    public function findUserComment($id, $user_id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->andWhere('r.user_id = :user_id')
            ->andWhere('r.comment IS NOT NULL')
            ->setParameter('id', $id)
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
