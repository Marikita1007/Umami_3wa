<?php

namespace App\Controller;

use App\Repository\CuisinesRepository;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private SpoonacularReceipesAPIController $spoonacularReceipesAPIController;
    private RecipesController $recipesController;

    public function __construct(SpoonacularReceipesAPIController $spoonacularReceipesAPIController, RecipesController $recipesController)
    {
        $this->spoonacularReceipesAPIController = $spoonacularReceipesAPIController;
        $this->recipesController =$recipesController;
    }

    #[Route("/", name: "home")]
    public function index(CuisinesRepository $cuisinesRepository, RecipesRepository $recipesRepository): Response
    {
        $category = '';

//        $getSpoonacularRandomRecipes = $this->spoonacularReceipesAPIController->getSpoonacularRandomRecipes();
        $getCuisineCategories = $this->spoonacularReceipesAPIController->getCuisineCategories($category);
        $getRecipes = $this->recipesController->showRecipes(); // Call function to show recipes from database

        // Get only the first 6 recipes to pass to the template
        $sixRecipes = array_slice($getRecipes, 0, 6);



        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
//            'jsonDataSpoonacular' => $getSpoonacularRandomRecipes,
            'getCuisineCategories' => $getCuisineCategories,
            'sixRecipes' => $sixRecipes,
            'topSevenCuisines' => $cuisinesRepository->findTopSevenCuisines(),
            'differentCuisines' => $recipesRepository->differentCuisines(),
        ]);
    }
}
