<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\Recipes;
use App\Entity\User;
use App\Form\CuisinesType;
use App\Form\FilterSearchType;
use App\Form\IngredientRecipeType;
use App\Form\RecipesType;
use App\Repository\IngredientsRepository;
use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Controller used to manage recipes contents in the public site.
 */
#[Route("/recipes")]
class RecipesController extends AbstractController
{
    private RecipesRepository $recipeRepository;

    public function __construct(RecipesRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * TODO MARIKA FIX THIS or CREATE Another solution for showing cards !!!!
     *
     * @return array An array of Recipe objects representing all available recipes.
     *
     */
    public function showRecipes()
    {
        return $this->recipeRepository->findAll();
    }

    #[Route("/list", name: "list_recipes", methods: ["GET"])]
    public function listAll(): Response
    {
        $user = $this->getUser();

        // Check if the user has the ROLE_ADMIN role
        if ($this->isGranted('ROLE_ADMIN')) {
            // If the user is an admin, get all recipes
            $recipes = $this->getDoctrine()->getRepository(Recipes::class)->findAll();
        } else {
            // If the user is not an admin, get only their own recipes
            $recipes = $this->getDoctrine()->getRepository(Recipes::class)->findBy(['user' => $user]);
        }

        return $this->render('recipes/recipes_dashboard.html.twig', [
            'recipes' => $recipes,
        ]);

    }

    /**
     * Display a single recipe based on its unique identifier.
     */
    #[Route("/recipe/{id}", name: "show_recipe", methods: ["GET"])]
    public function showRecipe(Recipes $recipe): Response
    {
        return $this->render('recipes/show_recipe.html.twig',
            ['recipe' => $recipe]);
    }

//    /**
//     * TODO MARIKA If edit both(recipes and ingredients) at same time is not a good solution, put this back
//     * Controller method for creating a new recipe.
//     *
//     * - Authenticated users can create recipes via a recipe form.
//     * - If not authenticated, it redirects to the login page with an error message.
//     *
//     * @Route("/new", name="new_recipe", methods={"GET", "POST"})
//     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
//     */
//    public function createNewRecipe(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
//    {
//        // Create a new Recipe entity
//        $recipe = new Recipes();
//
//        // Set user id to logged in user id
//        $user = $this->getUser();
//
//        if (!empty($user)) {
//            $recipe->setUser($user); // set user entity
//        } else {
//            $this->addFlash('error', 'You must be logged in to create recipe.');
//            return $this->redirectToRoute('/login'); // redirect user to login form
//        }
//
//        // Create a form for the Recipe entity
//        $form = $this->createForm(RecipesType::class, $recipe);
//
//        // Handle form submission
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            // Get the uploaded file
//            $imageFile = $form['image']->getData();
//
//            // Check if a file was uploaded
//            if ($imageFile) {
//                $this->saveImage($imageFile, $slugger, $recipe);
//            }
//
//            // Persist the Recipe entity
//            $entityManager->persist($recipe);
//            $entityManager->flush();
//
//            // Show message of success
//            $this->addFlash('success', 'Your recipe was created successfully');
//
//            // Redirect to the recipe details page
//            return $this->redirectToRoute('show_recipe', ['id' => $recipe->getId()]);
//        }
//
//        return $this->render('recipes/new_recipe.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * Controller method for creating a new recipe and a new ingredient.
     */
    #[Route("/new_recipe_ingredients", name: "new_recipe", methods: ["POST", "GET"])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function createNewRecipeAndIngredients(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        //TODO MARIKA This needs to be fixed. This Contorller is only inserting one ingredient. Ths problem comes from js. Evrytime I add a new ingredients field. it returns NaN or null as array number.
        $recipe = new Recipes();

        // Set the user association on the recipe
        $user = $this->getUser();
        $recipe->setUser($user);

        // create a form with Recipes and Ingredients entities
        $form = $this->createForm(IngredientRecipeType::class, [
            'recipe' => $recipe,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the uploaded file from recipe form
            $imageFile = $form['recipe']['image']->getData();

            // Check if a file was uploaded
            if ($imageFile) {
                $this->saveImage($imageFile, $slugger, $recipe);
            }

            // Form handler to insert into Recipes and Ingredients tables
            $data = $form->getData();
            $recipe = $data['recipe'];

            // Iterate over ingredients and add each one to the recipe
            $ingredients = $data['ingredients'];
            foreach ($ingredients as $ingredient) {
                $recipe->addIngredient($ingredient);
                $entityManager->persist($ingredient);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'The new recipe with ingredients are created !');
            return $this->redirectToRoute('list_recipes');
        }

        return $this->render('recipes/new_recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{id}", name: "edit_recipe", methods: ["GET", "POST"])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function editRecipeAndIngredients(Request $request, EntityManagerInterface $entityManager, Recipes $recipe, IngredientsRepository $ingredientsRepository, SluggerInterface $slugger): Response
    {
        // TODO MARIKA アクセス権限の確認: ユーザーが作成したレシピであるか、またはROLE_ADMIN権限を持っているか確認
        $this->denyAccessUnlessGranted('EDIT', $recipe);

        $ingredients = $ingredientsRepository->findBy(['recipe' => $recipe]);

        $form = $this->createForm(IngredientRecipeType::class, [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $imageFile = $form['recipe']['image']->getData();
            if ($imageFile) {
                $this->saveImage($imageFile, $slugger, $recipe);
            }

            // Form handler to insert into Recipes and Ingredients tables
            $data = $form->getData();
            $recipe = $data['recipe'];

            // Iterate over ingredients and add each one to the recipe
            $newIngredients = $data['ingredients'];

            foreach ($newIngredients as $newIngredient) {
                $recipe->addIngredient($newIngredient);
                $entityManager->persist($newIngredient);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recipe and ingredients have been updated.');

            return $this->redirectToRoute("show_recipe", ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: "delete_recipe", methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function deleteRecipe(EntityManagerInterface $entityManager, Request $request, Recipes $recipe): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $recipe);

        $entityManager->remove($recipe);
        $entityManager->flush();
        $this->addFlash('success', 'Your recipe was deleted');
        return $this->redirectToRoute("list_recipes");
    }

    #[Route('/recipes_all_filters', name: 'app_recipes_all_filters', methods: ['GET', 'POST'])]
    public function searchRecipesByFilters(
        RecipesRepository $recipesRepository,
        Request $request
    ): Response
    {
        $formFilterSearch = $this->createForm(FilterSearchType::class);
        $formFilterSearch->handleRequest($request);

        $formCategories = $this->createForm(CuisinesType::class);
        $formCategories->handleRequest($request);

        if ($formFilterSearch->isSubmitted() && $formFilterSearch->isValid()){
            $data = $formFilterSearch->getData();
            $word = $data['word'];

            $category = $formCategories->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByWord' => $recipesRepository->getByName($word),
                'formFilterSearch' => $formFilterSearch->createView(),
                'formCategories' => $formCategories->createView(),
            ]);
        }

        if ($formCategories->isSubmitted() && $formCategories->isSubmitted() ){
            $data = $formCategories->getData();
            $category = $formCategories->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByCategory' => $recipesRepository->findByCategory($category),
                'formCategories' => $formCategories->createView(),
                'formFilterSearch' => $formFilterSearch->createView(),
            ]);
        }

        return $this->render('recipes/recipes_filters.html.twig', [
            'recipes' => $recipesRepository->findAll(),
            'formFilterSearch' => $formFilterSearch->createView(),
            'formCategories' => $formCategories->createView(),
        ]);
    }

    private function saveImage(UploadedFile $imageFile, SluggerInterface $slugger, $recipe)
    {
        // Get the original file name and extension
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $imageFile->getClientOriginalExtension();

        // Generate a unique name for the file
        $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        // Move the file to the desired directory (you can configure this)
        try {
            $imageFile->move(
                $this->getParameter('recipe_image_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            throw new NotFoundHttpException('An error occurred while uploading the file.');
        }

        // Update the 'image' property of your entity to store the file name
        // instead of its contents
        $recipe->setImage($newFilename);
    }
}

