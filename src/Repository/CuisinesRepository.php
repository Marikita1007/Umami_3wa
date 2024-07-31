<?php

namespace App\Repository;

use App\Entity\Cuisines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cuisines>
 *
 * @method Cuisines|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuisines|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuisines[]    findAll()
 * @method Cuisines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuisinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuisines::class);
    }

    public function add(Cuisines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cuisines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrieves the top seven cuisines based on the number of associated recipes.
     *
     * @return array An array of Cuisine entities with an additional 'recipeCount' field.
     */
    public function findTopSevenCuisines(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'COUNT(r.id) as recipeCount') // Select the Cuisine entity and count of associated recipes
            ->leftJoin('c.recipes', 'r')
            ->groupBy('c.id')
            ->orderBy('recipeCount', 'DESC')
            ->setMaxResults(7) // Limit the result set to the top seven cuisines
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This DQL checks if a cuisine is used by at least a recipe.
     */
    public function isCuisineNameUsed(int $cuisineId): bool
    {
        // Directly query the Recipe entity to check if there are recipes with the given cuisine ID
        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(r.id)')
            ->leftJoin('c.recipes', 'r')
            ->where('c.id = :cuisineId')
            ->setParameter('cuisineId', $cuisineId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
