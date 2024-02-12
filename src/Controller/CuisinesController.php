<?php

namespace App\Controller;

use App\Entity\Cuisines;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted("ROLE_USER")]
class CuisinesController extends AbstractController
{

    /**
     * Displays a list of cuisines.
     *
     * @return Response  A Symfony Response object rendering the cuisines page with a list of all cuisines.
     */
    #[Route("/cuisines", name: "cuisines", methods: ["GET"])]
    public function index(): Response
    {
        $cuisines = $this->getDoctrine()
            ->getRepository(Cuisines::class)
            ->findAll();

        return $this->render('cuisines/cuisines.html.twig', [
            'cuisines' => $cuisines,
        ]);
    }

    /**
     * Retrieves and returns a JSON response containing a list of all cuisines.
     *
     * @return JsonResponse  A Symfony JsonResponse object containing a list of cuisines in JSON format.
     */
    #[Route("/cuisines/list", name: "cuisines_list", methods: ["GET"])]
    public function showAll(): JsonResponse
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

    /**
     * Retrieves and returns a JSON response containing details of a specific cuisine.
     *
     * @param int $id  The unique identifier of the cuisine.
     *
     * @return JsonResponse  A Symfony JsonResponse object containing the details of the specified cuisine in JSON format.
     */
    #[Route("/cuisines/show/{id}", name: "cuisines_show", methods: ["GET", "POST"])]
    public function show(int $id): JsonResponse
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

    /**
     * Creates a new cuisine and stores it in the database.
     *
     * @param Request             $request   The HTTP request object containing the cuisine data.
     * @param ValidatorInterface  $validator Symfony's validator service for validating the cuisine entity.
     *
     * @return JsonResponse  A Symfony JsonResponse indicating the success or failure of creating a new cuisine.
     */
    #[Route("/cuisines/new", name: "cuisines_new", methods: ["POST"])]
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cuisine = new Cuisines();
        $cuisine->setName($request->request->get('name'));

        // Validate the entity using Symfony's validator
        $errors = $validator->validate($cuisine);

        if (count($errors) > 0) {
            // There are validation errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json(['messages' => ['errors' => $errorMessages]], 400); // HTTP 400 Bad Request
        }

        $entityManager->persist($cuisine);
        $entityManager->flush();

        return $this->json('Created new cuisines successfully with id ' . $cuisine->getId());
    }

    /**
     * Edits an existing cuisine based on its unique identifier.
     *
     * @return JsonResponse  A Symfony JsonResponse indicating the success or failure of editing the cuisine.
     */
    #[Route("/cuisines/edit/{id}", name: "cuisine_edit", methods: ["PUT"])]
    public function edit(Request $request, int $id, ValidatorInterface $validator): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cuisine = $entityManager->getRepository(cuisines::class)->find($id);

        if (!$cuisine) {
            return $this->json('No cuisines found for id' . $id, 404);
        }

        $cuisine->setName($request->request->get('name'));

        // Validate the entity using Symfony's validator
        $errors = $validator->validate($cuisine);

        if (count($errors) > 0) {
            // There are validation errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json(['messages' => ['errors' => $errorMessages]], 400); // HTTP 400 Bad Request
        }

        $entityManager->flush();

        $data = [
            'id' => $cuisine->getId(),
            'name' => $cuisine->getName(),
        ];

        return $this->json($data);
    }

    /**
     * Deletes an existing cuisine based on its unique identifier.
     *
     * @return JsonResponse  A Symfony JsonResponse indicating the success or failure of deleting the cuisine.
     */
    #[Route("/cuisines/delete/{id}", name: "cuisine_delete", methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cuisine = $entityManager->getRepository(cuisines::class)->find($id);

        if (!$cuisine) {
            return $this->json('No cuisine found for id' . $id, 404);
        }

        $entityManager->remove($cuisine);
        $entityManager->flush();

        return $this->json('Deleted a cuisine successfully with id ' . $id);
    }
}