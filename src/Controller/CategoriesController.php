<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{

    private ReceipesAPIController $receipesAPIController;

    public function __construct(ReceipesAPIController $receipesAPIController)
    {
        $this->receipesAPIController = $receipesAPIController;
    }

    /**
     * @Route("/categories", name="categories", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('categories/categories.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    /**
     * @Route("/category/{cuisine}", name="show_categories")
     */
    public function show($cuisine)
    {
        $getCuisineCategories = $this->receipesAPIController->getCuisineCategories($cuisine);

        return $this->render('categories/categories.html.twig', [
            'cuisine' => $cuisine,
            'getCuisineCategories' => $getCuisineCategories,
        ]);
    }
}
