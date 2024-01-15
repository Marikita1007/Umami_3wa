<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CategoriesController extends AbstractController
{

    #[Route('/categories', name: 'categories', methods: ['GET'])]
    public function index(): Response
    {
        // Render a Twig template for the "categories" route.
        return $this->render('categories/categories.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    #[Route('/category/{cuisine}', name: 'show_categories')]
    public function show($cuisine, HttpClientInterface $httpClient)
    {

        // Render a Twig template for the "show_categories" route, passing data to the template.
        return $this->render('categories/categories.html.twig', [
            'cuisine' => $cuisine,
        ]);
    }
}
