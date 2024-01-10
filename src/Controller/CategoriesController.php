<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// Import the SpoonacularReceipesAPIController for API calls.
use App\Controller\SpoonacularReceipesAPIController;

//TODO MARIKA If I don't use Spooncular, delete this !!!!!
// TODO MARIKA Maybe move this to SpoonacularReceipesAPIController.php。
class CategoriesController extends AbstractController
{
    // Dependency injection: Inject the SpoonacularReceipesAPIController into the controller.
    private SpoonacularReceipesAPIController $receipesAPIController;

    public function __construct(SpoonacularReceipesAPIController $receipesAPIController)
    {
        $this->receipesAPIController = $receipesAPIController;
    }

    #[Route('/categories', name: 'categories', methods: ['GET'])]
    public function index(): Response
    {
        // Render a Twig template for the "categories" route.
        return $this->render('categories/categories.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    #[Route('/category/{cuisine}', name: 'show_categories')]
    public function show($cuisine, SpoonacularReceipesAPIController $spoonacularReceipesAPIController, HttpClientInterface $httpClient)
    {
        //TODO MARIKA Check if I need Spponacular or not !!!
        // Call a method from the SpoonacularReceipesAPIController to get cuisine categories.
        $getCuisineCategories = $this->receipesAPIController->getCuisineCategories($cuisine);
        $cuisineDetails = $spoonacularReceipesAPIController->showCuisineDetails($cuisine, $httpClient);

        // Render a Twig template for the "show_categories" route, passing data to the template.
        return $this->render('categories/categories.html.twig', [
            'cuisine' => $cuisine,
            'getCuisineCategories' => $getCuisineCategories,
            'spoonaCularURL' => 'https://spoonacular.com/'
        ]);
    }
}
