<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
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
        $this->spoonacularApiKey = $_ENV['SPOONACULAR_API_KEY'];
        $this->apiDataStorage = $apiDataStorage;
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/get_spoonacular_recipe", name="get_spoonacular_recipe", methods={"GET"})
     */
    public function getSpoonacularRandomRecipes()
    {
        // To keep data, first we check cache data and if it exists, we return it
        $cachedData = $this->apiDataStorage->get('spoonacular_recipes');
        if ($cachedData) {
            return $this->json($cachedData);
        }

        // If not cache, we get data from API
        $apiEndpoint = 'https://api.spoonacular.com/recipes/random';

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint, [
                'query' => [
                    'apiKey' => $this->spoonacularApiKey,
                    'number' => 9, // Number of API data we receive
                ],
            ]);
            // If http status code is 200 (OK)
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $data = $response->toArray();
                // Save the data in cache
                $this->apiDataStorage->set('spoonacular_recipes', $data);
                // return $response->toArray(); MARIKA : 開発注は何度も呼び出すのを避けるため$data内の保存したレシピを使用。
                return $this->json($data);
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // If http status code is 402 (Payment Required)
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // If http error code is not 402 (404, 505 etc)
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP error process
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/get_cuisine_categories", name="get_cuisine_categories", methods={"GET"})
     */
    public function getCuisineCategories($cuisine)
    {
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

            // HTTPステータスコード200（OK）の場合
            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            } elseif ($response->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED) {
                // HTTPステータスコード402（Payment Required）の場合
                return $this->json(['error' => 'Payment Required'], Response::HTTP_PAYMENT_REQUIRED);
            } else {
                // 402以外のHTTPエラーコード（例: 404、500など）に対するエラーハンドリング
                return $this->json(['error' => 'HTTP error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (HttpExceptionInterface $e) {
            // HTTP エラーの場合の処理
            return $this->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
