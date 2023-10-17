<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
//MARIKA TODO Delete non used uses !!
//use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
//use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
//use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
//use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
//use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\TestData\ApiDataStorage;

/**
 * @Route("/api", name="api_", methods={"GET"})
 */
class SpoonacularReceipesAPIController extends AbstractController
{
    private $spoonacularApiKey;
    private ApiDataStorage $apiDataStorage;
    private HttpClientInterface $httpClient;

    public function __construct(ApiDataStorage $apiDataStorage, HttpClientInterface $httpClient)
    {
        // Initialize API-related dependencies through dependency injection.
        $this->spoonacularApiKey = $_ENV['SPOONACULAR_API_KEY'];
        $this->apiDataStorage = $apiDataStorage;
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/get_spoonacular_recipe", name="get_spoonacular_recipe", methods={"GET"})
     */
    public function getSpoonacularRandomRecipes()
    {
        // Check if cached data exists and return it if available.
        $cachedData = $this->apiDataStorage->get('spoonacular_recipes');
        if ($cachedData) {
            return $this->json($cachedData);
        }

        // If no cached data, make an API request to get random recipes from Spoonacular.
        $apiEndpoint = 'https://api.spoonacular.com/recipes/random';

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->spoonacularApiKey,
                    'number' => 9, // Number of API data we receive
                ],
            ]);
            // Check the HTTP status code and handle responses accordingly.
            // For HTTP status code 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                // Convert the response to an array and save it in the cache.
                $data = $response->toArray();
                $this->apiDataStorage->set('spoonacular_recipes', $data);
                // Return $response->toArray(); MARIKA : 開発注は何度も呼び出すのを避けるため$data内の保存したレシピを使用。
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
     * @Route("/get_cuisine_categories", name="get_cuisine_categories", methods={"GET"})
     */
    public function getCuisineCategories($cuisine)
    {
        // Make an API request to get cuisine categories from Spoonacular.
        $apiEndpoint = 'https://api.spoonacular.com/recipes/complexSearch';

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->spoonacularApiKey,
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

}
