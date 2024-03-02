<?php

namespace App\Repository;

use App\Entity\Postimage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postimage>
 *
 * @method Postimage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postimage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postimage[]    findAll()
 * @method Postimage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostimageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postimage::class);
    }

//    /**
//     * @return Postimage[] Returns an array of Postimage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Postimage
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
