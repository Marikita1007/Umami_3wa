<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private SpoonacularReceipesAPIController $receipesAPIController;
    private RecipesController $recipesController;

    public function __construct(SpoonacularReceipesAPIController $receipesAPIController, RecipesController $recipesController)
    {
        $this->receipesAPIController = $receipesAPIController;
        $this->recipesController = $recipesController;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $category = '';

        $getSpoonacularRandomRecipes = $this->receipesAPIController->getSpoonacularRandomRecipes();
        $getCuisineCategories = $this->receipesAPIController->getCuisineCategories($category);
        $getRecipes = $this->recipesController->showRecipes(); // Call function to show recipes from database

        // Get only the first 6 recipes to pass to the template
        $sixRecipes = array_slice($getRecipes, 0, 6);

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'jsonDataSpoonacular' => $getSpoonacularRandomRecipes,
            'getCuisineCategories' => $getCuisineCategories,
            'sixRecipes' => $sixRecipes
        ]);
    }
}
