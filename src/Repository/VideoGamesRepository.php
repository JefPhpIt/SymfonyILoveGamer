<?php

namespace App\Repository;

use App\Entity\VideoGames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoGames>
 *
 * @method VideoGames|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoGames|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoGames[]    findAll()
 * @method VideoGames[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoGamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGames::class);
    }

//    /**
//     * @return VideoGames[] Returns an array of VideoGames objects
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

//    public function findOneBySomeField($value): ?VideoGames
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
