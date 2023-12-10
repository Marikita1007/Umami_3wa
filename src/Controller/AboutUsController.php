<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    #[Route('/about_us', name: 'about_us')]
    public function index(): Response
    {
        return $this->render('about_us/about_us.html.twig', [
            'controller_name' => 'AboutUsController',
        ]);
    }
}
