<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Import the SpoonacularReceipesAPIController for API calls.
use App\Controller\SpoonacularReceipesAPIController;

class CategoriesController extends AbstractController
{
    // Dependency injection: Inject the SpoonacularReceipesAPIController into the controller.
    private SpoonacularReceipesAPIController $receipesAPIController;

    public function __construct(SpoonacularReceipesAPIController $receipesAPIController)
    {
        // Initialize the API controller through dependency injection.
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
    public function show($cuisine)
    {
        // Call a method from the SpoonacularReceipesAPIController to get cuisine categories.
        $getCuisineCategories = $this->receipesAPIController->getCuisineCategories($cuisine);

        // Render a Twig template for the "show_categories" route, passing data to the template.
        return $this->render('categories/categories.html.twig', [
            'cuisine' => $cuisine,
            'getCuisineCategories' => $getCuisineCategories,
        ]);
    }
}
