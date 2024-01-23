<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CuisinesRepository;
use App\Repository\RecipesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    private RecipesController $recipesController;

    public function __construct(RecipesController $recipesController)
    {
        $this->recipesController =$recipesController;
    }

    #[Route("/", name: "home")]
    public function index(CuisinesRepository $cuisinesRepository, RecipesRepository $recipesRepository): Response
    {
        $category = '';

        $getRecipes = $this->recipesController->showRecipes(); // Call function to show recipes from database

        // Get only the first 9 recipes to pass to the template
        $nineLatestRecipes = $recipesRepository->findlatestRecipes();

        return $this->render('home/home.html.twig', [
            'nineLatestRecipes' => $nineLatestRecipes, // get latest recipes that created
            'topSevenCuisines' => $cuisinesRepository->findTopSevenCuisines(), // get 7 top created recipes
            'differentCuisines' => $recipesRepository->differentCuisines(), // get random cuisines
            'topLikedRecipes' => $recipesRepository->findTopLikedRecipes(),
        ]);
    }

}
