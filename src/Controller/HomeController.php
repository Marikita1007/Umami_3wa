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
            'controller_name' => 'HomeController',
            'nineLatestRecipes' => $nineLatestRecipes,
            'topSevenCuisines' => $cuisinesRepository->findTopSevenCuisines(),
            'differentCuisines' => $recipesRepository->differentCuisines(),
        ]);
    }

    #[Route("/toggle-dark-mode", name: "get_dark_mode_state", methods: ("POST"))]
    public function getDarkModeState(): JsonResponse
    {
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            $isDarkModeActive = $user->getIsDarkModeActive();
            return new JsonResponse(['isDarkModeActive' => $isDarkModeActive]);
        }

        return new JsonResponse(['isDarkModeActive' => false]);
    }

    #[Route("/toggle-dark-mode", name: "toggle_dark_mode", methods: ("POST"))]
    public function toggleDarkMode(Request $request): JsonResponse
    {
        $isDarkModeActive = $request->request->get('isDarkModeActive');
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            $user->setIsDarkModeActive($isDarkModeActive);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false, 'message' => 'User not authenticated.']);
    }

}
