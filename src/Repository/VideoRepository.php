<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    //    /**
    //     * @return Video[] Returns an array of Video objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Video
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchByTitle(string $title): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchByCategory(string $category): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.category LIKE :category')
            ->setParameter('category', '%' . $category . '%')
            ->getQuery()
            ->getResult();
    }

    public function getRecommendations($user_id): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT v
    FROM App\Entity\Video v
    JOIN v.genre g
    WHERE v.id NOT IN (
        SELECT v2.id
        FROM App\Entity\Video v2
        JOIN App\Entity\Review r WITH r.video_id = v2.id
        WHERE r.user_id = :user_id
        AND r.thumbsUp = 1
    )
    AND g.id IN (
        SELECT g2.id
        FROM App\Entity\Genre g2
        JOIN g2.videos v3
        JOIN v3.reviews r2 WITH r2.video_id = v3.id
        WHERE r2.user_id = :user_id
        AND r2.thumbsUp = 1
    )'
    )->setParameter('user_id', $user_id);

    return $query->getResult();
}


}
