<?php

namespace App\Repository;

use App\Entity\Cuisines;
use App\Entity\Recipes;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipes>
 *
 * @method Recipes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipes[]    findAll()
 * @method Recipes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipes::class);
    }

    public function findByCuisine(Cuisines $cuisine)
    {
        return $this
            ->createQueryBuilder('r')
            ->join('r.cuisine', 'c')
            ->where('c.name = :cuisineName')
            ->setParameter('cuisineName', $cuisine->getName())
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByName(string $word)
    {
        return $this
            ->createQueryBuilder('r')
            ->where('r.name LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findUserRecipes($user): array
    {
        return $this
            ->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Recipes[] Returns an array of Recipes objects
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


//    /**
//     * @return Recipes[] Returns an array of Recipes objects
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

//    public function findOneBySomeField($value): ?Recipes
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
