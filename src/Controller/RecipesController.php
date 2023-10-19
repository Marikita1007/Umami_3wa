<?php

namespace App\Controller;

use App\Repository\RecipesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipes")
 */
class RecipesController extends AboutUsController
{
    private RecipesRepository $recipeRepository;

    public function __construct(RecipesRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @Route("/show", name="show_recipes")
     */
    public function showRecipes()
    {
        return $this->recipeRepository->findAll();
    }
}