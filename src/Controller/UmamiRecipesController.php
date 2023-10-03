<?php

namespace App\Controller;

use App\Entity\UmamiReceipes;
use App\Repository\UmamiReceipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UmamiRecipesController extends AbstractController
{

    /**
     * @Route("/recipes", name="recipes")
     */
    public function index(UmamiReceipesRepository $repository): Response
    {
        $recipes = $repository->findAllWithDetails();

        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }


}
