<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private ReceipesAPIController $receipesAPIController;

    public function __construct(ReceipesAPIController $receipesAPIController)
    {
        $this->receipesAPIController = $receipesAPIController;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $category = '';

        $getSpoonacularRandomRecipes = $this->receipesAPIController->getSpoonacularRandomRecipes();
        $getCuisineCategories = $this->receipesAPIController->getCuisineCategories($category);

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'jsonDataSpoonacular' => $getSpoonacularRandomRecipes,
            'getCuisineCategories' => $getCuisineCategories,
        ]);
    }
}
