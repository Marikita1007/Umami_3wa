<?php

namespace App\Controller;

use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Recipes;


//MARIKA TODO : ADD SECURITY conditions / Comments codes / Fix comments of error handlings / FIx Updated At
/**
 * @Route("/api", name="api_")
 */
class RecipesApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Recipes $recipes;
    private RecipesRepository $recipesRepository;

    public function __construct(EntityManagerInterface $entityManager, Recipes $recipes, RecipesRepository $recipesRepository)
    {
        $this->entityManager = $entityManager;
        $this->recipes = $recipes;
        $this->recipesRepository = $recipesRepository;
    }

    /**
     * @Route("/recipe", name="recipes_show", methods={"GET"})
     */
    public function index(): Response
    {
        $recipes = $this->recipesRepository->findAll();

        $data = [];

        foreach ($recipes as $recipe) {
            $data[] = [
                'id'            => $recipe->getId(),
                'name'          => $recipe->getName(),
                'description'   => $recipe->getDescription(),
                'instructions'  => $recipe->getInstructions(),
                'image'         => $recipe->getImage(),
                'prep_time'     => $recipe->getPrepTime(),
                'servings'      => $recipe->getServings(),
                'cook_time'     => $recipe->getPrepTime(),
                'calories'      => $recipe->getCalories(),
                'difficulty'    => $recipe->getDifficulty()
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/recipe", name="recipe_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $setName = $request->request->get('name');
        $recipe = $this->recipes->setName($setName);

        if ($setName !== null) {
            $recipe->setName($setName);
        }

        $recipe->setDescription($request->request->get('description'));
        $recipe->setInstructions($request->request->get('instructions'));
        $recipe->setCreatedAt($request->request->get('created_at'));
        $recipe->setImage($request->request->get('instructions'));
        $recipe->setInstructions($request->request->get('image'));
        $recipe->setPrepTime($request->request->get('prep_time'));
        $recipe->setServings($request->request->get('servings'));
        $recipe->setCookTime($request->request->get('cook_time'));
        $recipe->setCalories($request->request->get('calories'));
        $recipe->setDifficulty($request->request->get('difficulty'));

        // Tell Doctrine to (eventually) save the Product (no queries yet)
        $this->entityManager->persist($recipe);

        // Actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();

        return $this->json('successfully created new recipe with id ' . $recipe->getId());
    }

    /**
     * @Route("/recipe/{id}", name="recipe_detail", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $recipe = $this->recipesRepository->find($id);

        if (!$recipe) {
            return $this->json('No recipe found for id' . $id, 404);
        }

        // Access the properties of the retrieved entity
        $data =  [
            'id'           => $recipe->getId(),
            'name'         => $recipe->getName(),
            'description'  => $recipe->getDescription(),
            'instructions' => $recipe->getInstructions(),
            'created_at'   => $recipe->getCreatedAt(),
            'image'        => $recipe->getImage(),
            'prep_time'    => $recipe->getPrepTime(),
            'servings'     => $recipe->getServings(),
            'cook_time'    => $recipe->getCookTime(),
            'calories'     => $recipe->getCalories(),
            'difficulty'   => $recipe->getDifficulty()
        ];

        return $this->json($data);
    }

    /**
     * @Route("/recipe/{id}", name="recipe_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
//        dd($request->request);
        $recipe = $this->recipesRepository->find($id);

        if (!$recipe) {
            return $this->json('No recipe found for id ' . $id, 404);
        }

        //MARIKA : From POSTMAN Send the properties with x-www-form-urlencoded
        //MARIKA : Make more Error handling here to make sure we don't accept securities vulnerabilities.

        // Check if the "name" is provided in the request and update it
        $newName = $request->request->get('name');

        if ($newName !== null) {
            $recipe->setName($newName);
        }


        // Define the properties and their corresponding setter methods
        $recipeProperties = [
            'name'         => 'setName',
            'description'  => 'setDescription',
            'instructions' => 'setInstructions',
//            'updated_at'   => 'setUpdatedAt',
            'image'        => 'setImage',
            'prep_time'    => 'setPrepTime',
            'servings'     => 'setServings',
            'cook_time'    => 'setCookTime',
            'calories'     => 'setCalories',
            'difficulty'   => 'setDifficulty',
        ];

        foreach ($recipeProperties as $requestKey => $setterMethod) {
            $newRecipeInfo = $request->request->get($requestKey);
            if ($newRecipeInfo !== null) {
                $recipe->$setterMethod($newRecipeInfo);
            }
        }

        $this->entityManager->flush();

        $data =  [
            'id'           => $recipe->getId(),
            'name'         => $recipe->getName(),
            'description'  => $recipe->getDescription(),
            'instructions' => $recipe->getInstructions(),
            'created_at'   => $recipe->getCreatedAt(),
            'updated_at'   => $recipe->getUpdatedAt(),
            'image'        => $recipe->getImage(),
            'prep_time'    => $recipe->getPrepTime(),
            'servings'     => $recipe->getServings(),
            'cook_time'    => $recipe->getCookTime(),
            'calories'     => $recipe->getCalories(),
            'difficulty'   => $recipe->getDifficulty()
        ];

        return $this->json($data);
    }

    /**
     * @Route("/recipe/{id}", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $recipe = $this->recipesRepository->find($id);

        if (!$recipe) {
            return $this->json('No recipe found for id' . $id, 404);
        }

        $this->entityManager->remove($recipe);
        $this->entityManager->flush();

        return $this->json('Deleted a recipe successfully with id ' . $id);
    }


}