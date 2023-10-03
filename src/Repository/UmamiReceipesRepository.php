<?php

namespace App\Repository;

use App\Entity\UmamiReceipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UmamiReceipes>
 *
 * @method UmamiReceipes|null find($id, $lockMode = null, $lockVersion = null)
 * @method UmamiReceipes|null findOneBy(array $criteria, array $orderBy = null)
 * @method UmamiReceipes[]    findAll()
 * @method UmamiReceipes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UmamiReceipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UmamiReceipes::class);
    }


    /**
     * @return UmamiReceipes[] Returns an array of UmamiReceipes objects
     */
    public function findAllWithDetails()
    {
        return $this->createQueryBuilder('r')
            ->select('r.id, r.title, r.description')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return UmamiReceipes[] Returns an array of UmamiReceipes objects
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

//    public function findOneBySomeField($value): ?UmamiReceipes
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
