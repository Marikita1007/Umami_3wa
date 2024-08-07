<?php

namespace App\Controller;

use App\TestData\ApiDataStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Controller managing API requests to TheMealDb for random recipes.
 */
#[Route("/api-the-meal-db", name: "api_the_meal_db",methods: ["GET"])]
class TheMealDbAPIController extends AbstractController
{
    private $theMealDbApiKey;
    private ApiDataStorage $apiDataStorage;
    private HttpClientInterface $httpClient;

    public function __construct(ApiDataStorage $apiDataStorage, HttpClientInterface $httpClient)
    {
        // Initialize API-related dependencies through dependency injection.
        $this->theMealDbApiKey = $_ENV['THE_MEAL_DB_API_KEY'];
        $this->apiDataStorage = $apiDataStorage;
        $this->httpClient = $httpClient;
    }

    /**
     * Gets random recipes from TheMealDb API.
     *
     * @return JsonResponse  A Symfony JsonResponse object containing the MealDB API response.
     */
    public function gettheMealDbRandomRecipes()
    {
        // Check if cached data exists and return it if available.
        $cachedData = $this->apiDataStorage->get('the_meal_db_recipes');
        if ($cachedData) {
            return $this->json($cachedData);
        }

        // If no cached data, make an API request to get random recipes from theMealDb.
        $apiEndpoint = 'https://www.themealdb.com/api/json/v1/1/random.php';

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'number' => 1, // Number of API data we receive
                ],
            ]);
            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                // Convert the response to an array and save it in the cache.
                $data = $response->toArray();
                $this->apiDataStorage->set('theMealDb_recipes', $data);
                return $this->json($data);
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // For HTTP status code error is not 402 (404, 505 etc)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Gets cuisine categories from TheMealDb API based on the provided cuisine.
     *
     * @param string $cuisine  The cuisine for which countries are requested.
     *
     * @return JsonResponse  A Symfony JsonResponse object containing the API response.
     */
    public function getCuisineCategories($cuisine)
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = 'https://www.themealdb.com/api/json/v1/1/categories.php';

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'cuisine' => $cuisine,
                    'number' => 10,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Shows details of a cuisine from TheMealDb API based on the provided ID.
     *
     * @param int                 $id      The ID of the cuisine.
     * @param HttpClientInterface $client  The Symfony HttpClientInterface for making API requests.
     *
     * @return JsonResponse  A Symfony JsonResponse object containing the API response.
     */
    public function showCuisineDetails($id, HttpClientInterface $client): JsonResponse
    {
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/lookup.php?i={$id}";

        try {
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return new JsonResponse($response->toArray());
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                return new JsonResponse(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                return new JsonResponse(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return new JsonResponse(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Gets recipes from TheMealDb API based on the provided meal category.
     *
     * @param string $mealCategory  The meal category for which recipes are requested.
     *
     */
    public function sameCategoryRecipes($mealCategory)
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/filter.php?c={$mealCategory}";

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'c' => $mealCategory,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {

                $meals = $response->toArray();

                //Limit API response to 3 meals qnd get random meals
                $randomMeals = array_rand($meals['meals'], min(3, count($meals['meals'])));
                $selectedMeals = array_intersect_key($meals['meals'], array_flip($randomMeals));

                return ['meals' => $selectedMeals];
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieves meals from The MealDB API based on the provided meal name.
     *
     * @param string $word  The meal name for which meals are requested.
     *
     */
    public function getTheMealDbMealsByName($word)
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/search.php?s={$word}";

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'w' => $word,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieves meals from The MealDB API based on the provided category name.
     *
     * @param string $categoryName  The category name for which meals are requested.
     *
     * @return array|JsonResponse Returns an array of meals for successful responses,
     *                           or a JsonResponse with error details for error responses.
     */
    public function getSameCategoryMeals($categoryName)
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/filter.php?c={$categoryName}";

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'c' => $categoryName,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Retrieves meals from The MealDB API based on the provided cuisine name.
     *
     * @param string $cuisineName The cuisine name for which meals are requested.
     *
     * @return array|JsonResponse Returns an array of meals for successful responses,
     *                           or a JsonResponse with error details for error responses.
     */
    public function getSameCuisineMeals($cuisineName)
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/filter.php?a={$cuisineName}";

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                    'area' => $cuisineName,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieves all categories from The MealDB API.
     *
     * @return array|JsonResponse Returns an array of categories for successful responses,
     *                           or a JsonResponse with error details for error responses.
     */
    public function getAllCategories()
    {
        // Make an API request to get cuisine categories from theMealDb.
        $apiEndpoint = "https://www.themealdb.com/api/json/v1/1/categories.php";

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->theMealDbApiKey,
                ],
            ]);

            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // For HTTP status code 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // Error handling for HTTP error codes other than 402 (e.g. 404, 500, etc.)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error handling
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}