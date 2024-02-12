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

    /**
     * Retrieves recipes based on a given cuisine.
     *
     * @param Cuisines $cuisine The cuisine for which recipes are requested.
     * @return array An array of Recipe entities associated with the specified cuisine.
     */
    public function findByCuisine(Cuisines $cuisine)
    {
        return $this
            ->createQueryBuilder('r')
            ->join('r.cuisine', 'c')
            ->where('c.name = :cuisineName') //:cuisineName is a placeholder that gets replaced with a sanitized version of $cuisine->getName().
            ->setParameter('cuisineName', $cuisine->getName()) // Using DQL with parameterized queries
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retrieves recipes based on a given category.
     *
     * @param Categories $category The category for which recipes are requested.
     * @return array An array of Recipe entities associated with the specified category.
     */
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

    /**
     * Retrieves recipes containing a specific word in their name.
     *
     * @param string $word The word to search for in recipe names.
     * @return array An array of Recipe entities matching the search criteria.
     */
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

    /**
     * Retrieves recipes created by a specific user.
     *
     * @param mixed $user The user for whom recipes are requested.
     * @return array An array of Recipe entities created by the specified user.
     */
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

    /**
     * Retrieves recipes based on a given cuisine ID.
     *
     * @param mixed $cuisineId The ID of the cuisine for which recipes are requested.
     * @return array An array of Recipe entities associated with the specified cuisine ID.
     */
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
     * Retrieves random recipes
     *
     * @return recipes[] Returns an array of random 4 cuisines objects
     */
    public function findRandomRecipes(): array
    {
        // This method selects a random set of recipes using a native SQL query.
        // The query uses the 'RAND()' function for randomness.
        // The ResultSetMapping (RSM) is configured to map the result set to the Recipes entity.
        // This approach avoids exposing the application to SQL injection as the query is carefully constructed.

        // Native SQL query to select random rows from 'cuisines' table
        $sql  = 'SELECT r.*, RAND() as rand
                    FROM recipes r
                    LEFT JOIN cuisines c ON c.id = r.cuisine_id
                    GROUP BY r.id
                    ORDER BY rand
                    LIMIT 4';

        // Create a ResultSetMapping (RSM) to map the result set to entities
        // This ensures that even when dealing with raw SQL, the application remains secure against potential threats.
        $rsm = new ResultSetMapping();

        // Add the main entity (Cuisines) to the ResultSetMapping
        $rsm->addEntityResult(Recipes::class, 'c');

        // Add field mappings for the 'id' field
        $rsm->addFieldResult('c', 'id', 'id');

        // Create a native query using EntityManager's createNativeQuery method
        // The native query uses a ResultSetMapping to map the result set to the Recipes entity.
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

    /**
     * Retrieves recipes with the same categories, excluding the specified recipe.
     *
     * @param array $recipeCategories An array of category IDs associated with the recipe.
     * @param mixed $recipeId The ID of the recipe to exclude from the results.
     * @return array An array of Recipe entities with similar categories, excluding the specified recipe.
     */
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

    /**
     * Retrieves the latest recipes based on the creation date.
     *
     * @param int $limit The maximum number of latest recipes to retrieve.
     * @return array An array of the latest Recipe entities.
     */
    public function findLatestRecipes(int $limit = 9): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC') //Ordering by latest recipes calling createdAt
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Retrieves the top liked recipes based on the number of likes.
     *
     * @param int $limit The maximum number of top liked recipes to retrieve.
     * @return array An array of the top liked Recipe entities.
     */
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
