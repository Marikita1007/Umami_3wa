<?php

namespace App\Controller;

use App\Entity\Cuisines;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class CuisinesController extends AbstractController
{
    #[Route("/cuisines", name: "cuisines", methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('cuisines/cuisines.html.twig', [
            'controller_name' => 'CuisinesController',
        ]);
    }

    #[Route("/cuisines/list", name: "cuisines_list", methods: ["GET"])]
    public function showAll(): Response
    {
        $cuisines = $this->getDoctrine()
            ->getRepository(Cuisines::class)
            ->findAll();

        $data = [];

        foreach ($cuisines as $cuisine) {
            $data[] = [
                'id' => $cuisine->getId(),
                'name' => $cuisine->getName(),
            ];
        }
        return $this->json($data);
    }

    #[Route("/cuisines/show/{id}", name: "cuisines_show", methods: ["GET", "POST"])]
    public function show(int $id): Response
    {
        $cuisines = $this->getDoctrine()
            ->getRepository(Cuisines::class)
            ->find($id);

        if (!$cuisines) {

            return $this->json('No cuisines found for id' . $id, 404);
        }

        $data = [
            'id' => $cuisines->getId(),
            'name' => $cuisines->getName(),
        ];

        return $this->json($data);
    }

    #[Route("/cuisines/new", name: "cuisines_new", methods: ["POST"])]
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cuisines = new Cuisines();
        $cuisines->setName($request->request->get('name'));

        $entityManager->persist($cuisines);
        $entityManager->flush();

        return $this->json('Created new cuisines successfully with id ' . $cuisines->getId());
    }

    #[Route("/cuisines/edit/{id}", name: "cuisines_edit", methods: ["PUT"])]
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cuisines = $entityManager->getRepository(cuisines::class)->find($id);

        if (!$cuisines) {
            return $this->json('No cuisines found for id' . $id, 404);
        }

        $cuisines->setName($request->request->get('name'));
        $entityManager->flush();

        $data = [
            'id' => $cuisines->getId(),
            'name' => $cuisines->getName(),
        ];

        return $this->json($data);
    }

    #[Route("/cuisines/delete/{id}", name: "cuisines_delete", methods: ["DELETE"])]
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cuisines = $entityManager->getRepository(cuisines::class)->find($id);

        if (!$cuisines) {
            return $this->json('No cuisines found for id' . $id, 404);
        }

        $entityManager->remove($cuisines);
        $entityManager->flush();

        return $this->json('Deleted a cuisines successfully with id ' . $id);
    }
}