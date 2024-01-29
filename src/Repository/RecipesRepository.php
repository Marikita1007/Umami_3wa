<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Cuisines;
use App\Entity\Recipes;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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

    public function findByCategories(Categories $category)
    {
        return $this
            ->createQueryBuilder('r')
            ->join('r.category', 'c')
            ->where('c.name = :cuisineName')
            ->setParameter('cuisineName', $category->getName())
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByWord(string $word)
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

    public function findByCuisineId($cuisineId): array
    {
        return $this
            ->createQueryBuilder('r')
            ->andWhere('r.cuisine = :cuisine')
            ->setParameter('cuisine', $cuisineId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return recipes[] Returns an array of random 4 cuisines objects
     */
    public function findRandomRecipes(): array
    {
        // Native SQL query to select random rows from 'cuisines' table
        $sql  = 'SELECT r.*, RAND() as rand
                    FROM recipes r
                    LEFT JOIN cuisines c ON c.id = r.cuisine_id
                    GROUP BY r.id
                    ORDER BY rand
                    LIMIT 4';

        // Create a ResultSetMapping (RSM) to map the result set to entities
        $rsm = new ResultSetMapping();

        // Add the main entity (Cuisines) to the ResultSetMapping
        $rsm->addEntityResult(Recipes::class, 'c');

        // Add field mappings for the 'id' field
        $rsm->addFieldResult('c', 'id', 'id');

        // Create a native query using EntityManager's createNativeQuery method
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        // Execute the query and return the result
        return $query->getResult();
    }

    /*
     * Get the number of likes for a specific recipe.
     *
     * @param int $recipeId The ID of the recipe.
     *
     * @return int The number of likes for the recipe.
     */
    public function getLikesCountForRecipe(int $recipeId): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(u.id)')
            ->join('r.likedUsers', 'u')
            ->where('r.id = :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getSameCategoriesRecipes($recipeCategories, $recipeId): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where(':categories MEMBER OF r.category')
            ->setParameter('categories', $recipeCategories)
            ->andWhere('r.id != :recipeId')
            ->setParameter('recipeId', $recipeId)
            ->orderBy('r.createdAt', 'DESC') // Order by createdAt in descending order
            ->setMaxResults(3);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findlatestRecipes(int $limit = 9): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC') //Ordering by latest recipes calling createdAt
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findTopLikedRecipes(int $limit = 2): array
    {
        $queryBuilder= $this->createQueryBuilder('r')
            ->select('r, COUNT(u.id) as likeCount')
            ->leftJoin('r.likedUsers', 'u')
            ->groupBy('r.id')
            ->orderBy('likeCount', 'DESC')
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }
}
