<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Cuisines;
use App\Entity\Ingredients;
use App\Entity\Photos;
use App\Entity\Recipes;
use App\Entity\User;
use App\Form\CategoriesType;
use App\Form\CommentsType;
use App\Form\CuisinesType;
use App\Form\FilterSearchType;
use App\Form\IngredientRecipeType;
use App\Form\RecipesType;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\CuisinesRepository;
use App\Repository\IngredientsRepository;
use App\Repository\PhotosRepository;
use App\Repository\RecipesRepository;
use App\Services\SimpleUploadService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Controller used to manage recipes contents in the public site.
 */
#[Route("/recipes")]
class RecipesController extends AbstractController
{
    private RecipesRepository $recipeRepository;
    private SimpleUploadService $simpleUploadService;

    public function __construct(RecipesRepository $recipeRepository, SimpleUploadService $simpleUploadService)
    {
        $this->recipeRepository = $recipeRepository;
        $this->simpleUploadService = $simpleUploadService;
    }

    /**
     * @return array An array of Recipe objects representing all available recipes.
     */
    public function showRecipes()
    {
        return $this->recipeRepository->findAll();
    }

    #[Route("/list", name: "list_recipes", methods: ["GET"])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function listAll(Request $request, RecipesRepository $recipesRepository): Response
    {
        $page = $request->query->getInt('page', 1); // Default page: 1
        $perPage = $request->query->getInt('perPage', 10); // Default display count: 10

        // Get the current user and search the database for recipes related to that user.
        $recipes = $recipesRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC'], // Order by creation date in descending order
            $perPage,
            ($page - 1) * $perPage
        );

        // Determine if there is a next page
        $hasNextPage = count($recipesRepository->findBy(['user' => $this->getUser()], [], 1, $page * $perPage)) > 0;

        // Determine if there is a previous page
        $hasPreviousPage = $page > 1;

        return $this->render('recipes/recipes_dashboard.html.twig', [
            'recipes' => $recipes,
            'page' => $page,
            'perPage' => $perPage,
            'hasNextPage' => $hasNextPage,
            'hasPreviousPage' => $hasPreviousPage,
        ]);
    }


    /**
     * Display a single recipe from The Meal DB API based on its unique identifier.
     */
    #[Route("/show-the-meal-db-recipe-details/{idMeal}", name: "show_the_meal_db_recipe", requirements: ['idMeal' => '\d+'], methods: ["GET", "POST"])]
    public function showTheMealDbRecipeDetails(
        int $idMeal,
        Request $request,
        TheMealDbAPIController $theMealDbAPIController,
        CommentsRepository $commentsRepository,
        HttpClientInterface $client): Response
    {
        $theMealdetails = $theMealDbAPIController->showCuisineDetails($idMeal, $client);
        $decodedTheMealDbData = json_decode($theMealdetails->getContent(), true);

        // Access to strCategory to get category of current meal from the Meal DB API
        // Call sameCategoriesRecipe method to get the same category recipe from the meal DB API
        $sameCategoryRecipes = $theMealDbAPIController->sameCategoryRecipes($decodedTheMealDbData['meals'][0]['strCategory']);

        // Retrieve The Meal DB data id and convert it to int
        $theMealDbId = (int)$decodedTheMealDbData['meals'][0]['idMeal'];

        // Add comments to the recipe
        $comments = new Comments();

        $form = $this->createForm(CommentsType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Set the user for the comment
            $comments->setUser($this->getUser());

            // Set the recipe for the comment (you may need to adjust this based on the API)
            $comments->setTheMealDbId($theMealDbId);

            // Persist the comment to your local database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comments);
            $entityManager->flush();

            // Include the comment content in the response
            return new JsonResponse([
                'success' => true,
                'content' => $comments->getContent(),
                'commentUsername' => $comments->getUser()->getUsername(),
                'datetime' => $comments->getDatetime()->format('F j, Y'),
                'flashMessage' => 'Your comment is registered.', //Json response containing the success flash message
            ]);
        }
        return $this->render('recipes/the_meal_db_api/show_recipe.html.twig', [
            'decodedTheMealDbData' => $decodedTheMealDbData,
            'sameCategoryRecipes' => $sameCategoryRecipes,
            'form' => $form->createView(),
            // Fetch comments with associated The Meal DB id information
            'comments' => $commentsRepository->findBy(['theMealDbId' => $theMealDbId], ['datetime' => 'ASC']),
            'theMealDbId' => $theMealDbId,
        ]);
    }

    /**
     * Display a single recipe based on its unique identifier.
     */
    #[Route("/recipe/{id}", name: "show_recipe", methods: ["GET", "POST"])]
    public function showRecipe(
        Recipes $recipe,
        PhotosRepository $photosRepository,
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        CommentsRepository $commentsRepository,
        RecipesRepository $recipesRepository ): Response
    {
        //Add comments to the recipe
        $comments = new Comments();

        $form = $this->createForm(CommentsType::class, $comments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            // Handle form errors
            $errors = [];

            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            foreach ($form->all() as $childForm) {
                if ($childForm instanceof FormInterface) {
                    $childErrors = $this->getFormErrors($childForm);
                    $errors = array_merge($errors, $childErrors);
                }
            }

            return new JsonResponse([
                'success' => false,
                'message' => 'Error submitting the comment. Please check your input.',
                'errors' => $errors,
            ]);
        }

        if($form->isSubmitted() && $form->isValid()) {

            foreach ($form->getErrors(true, true) as $error) {
                $errors[] = $error->getMessage();
            }

            // Set the user for the comment
            $comments->setUser($this->getUser());

            // Set the recipe for the comment
            $comments->setRecipe($recipe);

            $recipe->addComment($comments);
            $entityManager->persist($comments);
            $entityManager->flush();

            // Include the comment content in the response
            return new JsonResponse([
                'success' => true,
                'content' => $comments->getContent(),
                'commentUsername' => $comments->getUser()->getUsername(),
                'datetime' => $comments->getDatetime()->format('F j, Y'),
            ]);
        }

        return $this->render('recipes/show_recipe.html.twig', [
            'recipe' => $recipe,
            'LikesCountForRecipe' => $recipesRepository->getLikesCountForRecipe($recipe->getId()),
            'extraPhotos' => $photosRepository->findBy(['recipe' => $recipe]),
            'form' => $form->createView(),
            // Fetch comments with associated user information
            'comments' => $commentsRepository->findBy(['recipe' => $recipe], ['datetime' => 'DESC']),
            'categories' => $recipe->getCategory(), // Fetch categories associated with the recipe
            'sameCategoriesRecipes' => $recipesRepository->getSameCategoriesRecipes($recipe->getCategory(), $recipe->getId()),
        ]);
    }

    /**
     * Controller method for creating a new recipe and a new ingredient.
     */
    #[Route("/new_recipe_ingredients", name: "new_recipe", methods: ["POST", "GET"])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function createNewRecipeAndIngredients(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
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
            $thumbnailFile = $form['recipe']['thumbnail']->getData();

            // Check if a file was uploaded
            if ($thumbnailFile) {
                $this->addThumbnail($thumbnailFile, $recipe);
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

            // Get additional photos from Photos Entity
            $photos = $request->files->all();

            $images = $photos['ingredient_recipe']['recipe']['photos'] ?? null;
            if ($images) {
                $this->addExtraPhotos($images, $recipe);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            // Show flash message with recipe name
            $this->addFlash('success', sprintf('The new recipe "%s" with ingredients is created!', $recipe->getName()));

            return $this->redirectToRoute('list_recipes');
        }

        return $this->render('recipes/new_recipe.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/edit/{id}", name: "edit_recipe", methods: ["GET", "POST"])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function editRecipeAndIngredients(
        Request $request,
        Recipes $recipe,
        EntityManagerInterface $entityManager,
        IngredientsRepository $ingredientsRepository,
        PhotosRepository $photosRepository): Response
    {

        $this->denyAccessUnlessGranted('EDIT', $recipe);

        $ingredients = $ingredientsRepository->findBy(['recipe' => $recipe]);

        $form = $this->createForm(IngredientRecipeType::class, [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'current_thumbnail' => $recipe->getThumbnail(), // Set the current thumbnail
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $thumbnailFile = $form['recipe']['thumbnail']->getData();
            if ($thumbnailFile) {
                $this->addThumbnail($thumbnailFile, $recipe);
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

            //Get additional photos
            $photos = $request->files->all();

            $images = $photos['ingredient_recipe']['recipe']['photos'] ?? null;
            if ($images !== null) {
                $this->addExtraPhotos($images, $recipe);
            }


            $entityManager->persist($recipe);
            $entityManager->flush();

            // Show flash message with recipe name
            $this->addFlash('success', sprintf('The recipe "%s" with ingredients is updated.', $recipe->getName()));

            return $this->redirectToRoute("show_recipe", ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
            'photos' => $photosRepository->findBy(['recipe' => $recipe]),
            'current_thumbnail' => $recipe->getThumbnail(),
        ]);
    }

    #[Route("/delete/{id}", name: "delete_recipe", methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function deleteRecipe(EntityManagerInterface $entityManager, Request $request, Recipes $recipe): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $recipe);

        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('success', 'Your recipe was deleted.');

        return $this->redirectToRoute("list_recipes");
    }

    #[Route('/recipes_all_filters', name: 'app_recipes_all_filters', methods: ['GET', 'POST'])]
    public function searchRecipesByFilters(
        RecipesRepository $recipesRepository,
        CategoriesRepository $categoriesRepository,
        Request $request,
        TheMealDbAPIController $theMealDbAPIController,
        HttpClientInterface $client,
    ): Response
    {
        $formFilterSearch = $this->createForm(FilterSearchType::class);
        $formFilterSearch->handleRequest($request);

        $formCuisines = $this->createForm(CuisinesType::class);
        $formCuisines->handleRequest($request);

        $formCategories = $this->createForm(CategoriesType::class);
        $formCategories->handleRequest($request);

        $cuisineId = $request->query->get('cuisineId');

        // Response from the TheMealDbAPIController
        $theMealDbRecipeResponse = $theMealDbAPIController->gettheMealDbRandomRecipes();

        // Decode the JSON content
        $theMealDbRecipeData = json_decode($theMealDbRecipeResponse->getContent(), true);

        if ($formFilterSearch->isSubmitted() && $formFilterSearch->isValid()){
            $data = $formFilterSearch->getData();
            $word = $data['word'];

            $category = $formCuisines->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByWord' => $recipesRepository->getByWord($word),
                'formFilterSearch' => $formFilterSearch->createView(),
                'formCuisines' => $formCuisines->createView(),
                'formCategories' => $formCategories->createView(),
                'recipesByCuisine' => $recipesRepository->findByCuisineId($cuisineId),
                // Response from The MealDB API.
                'theMealDbMealsByName' => $theMealDbAPIController->getTheMealDbMealsByName($word),
            ]);
        }

        if ($formCuisines->isSubmitted() && $formCuisines->isValid()){

            $data = $formCuisines->getData();
            $cuisine = $formCuisines->get('name')->getData();

            $sameCuisineMeals = $theMealDbAPIController->getSameCuisineMeals($cuisine->getName());

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByCuisine' => $recipesRepository->findByCuisine($cuisine),
                'formCuisines' => $formCuisines->createView(),
                'formFilterSearch' => $formFilterSearch->createView(),
                'formCategories' => $formCategories->createView(),
                'theMealDbMealsByCuisine' => $sameCuisineMeals ?: [],
            ]);
        }

        if ($formCategories->isSubmitted() && $formCategories->isValid()){

            $data = $formCategories->getData();
            $category = $formCategories->get('name')->getData();
            $cuisine = $formCuisines->get('name')->getData();

            $sameCategoryMeals = $theMealDbAPIController->getSameCategoryMeals($category->getName());

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByCategories' => $recipesRepository->findByCategories($category),
                'formCuisines' => $formCuisines->createView(),
                'formCategories' => $formCategories->createView(),
                'formFilterSearch' => $formFilterSearch->createView(),
                'recipesByCuisineId' => $recipesRepository->findByCuisineId($cuisineId),
                'recipesByCuisine' => $cuisine ? $recipesRepository->findByCuisine($cuisine) : [],
                'theMealDbMealsByCategory' => $sameCategoryMeals ?: [],
            ]);
        }

        return $this->render('recipes/recipes_filters.html.twig', [
            'recipes' => $recipesRepository->findAll(),
            'formFilterSearch' => $formFilterSearch->createView(),
            'formCuisines' => $formCuisines->createView(),
            'formCategories' => $formCategories->createView(),
            'recipesByCuisine' => $recipesRepository->findByCuisineId($cuisineId),
            'theMealDbRecipe' => $theMealDbRecipeData,
        ]);
    }

    #[Route('/recipe/{id}/delete-photo/{imageId}', name: 'app_delete_photo_recipe', methods: ['GET', 'POST'])]
    public function deleteRecipePhoto(
        Recipes $recipe,
        EntityManagerInterface $entityManager,
        int $imageId,
        PhotosRepository $photosRepository): Response
    {
        $photoId = $photosRepository->find($imageId);
        if ($photoId && $photoId->getRecipe() === $recipe)
        {
            $entityManager->remove($photoId);
            $entityManager->flush();

            $this->addFlash('success', 'Your photo is successfully deleted');

            return $this->redirectToRoute('edit_recipe', ['id' => $recipe->getId()]);
        } else {
            $this->addFlash('danger', 'Error happened while deleting the photo ');
            return $this->redirectToRoute('app.recipes');
        }
    }

    #[Route('/{id}/like', name: 'recipe_like', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function recipeLike($id, LoggerInterface $logger, EntityManagerInterface $entityManager): JsonResponse
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Get the recipe based on the provided $id
        $recipe = $entityManager->getRepository(Recipes::class)->find($id);

        // Check if the recipe exists
        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        // Check if the user has already liked the recipe
        if ($user->getLikedRecipes()->contains($recipe)) {
            // Remove the liked recipe from the user's collection
            $user->removeLikedRecipe($recipe);
            $recipe->removeLikedUser($user); // Update the inverse side
            $liked = false;
        } else {
            // Add the liked recipe to the user's collection
            $user->addLikedRecipe($recipe);
            $recipe->addLikedUser($user); // Update the inverse side
            $liked = true;
        }

        // Persist the changes to the database
        $entityManager->flush();

        return $this->json(['liked' => $liked]);
    }

//    Refactor Marika upload file Refactored and using simpleUploadService for Thumbnail too
//    private function addThumbnail(UploadedFile $imageFile, $recipe)
//    {
//        // Get the original file name and extension
//        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
//
//        // Generate a unique name for the file
//        $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
//
//        // Move the file to the desired directory (you can configure this)
//        try {
//            $imageFile->move(
//                $this->getParameter('recipe_image_directory'),
//                $newFilename
//            );
//        } catch (FileException $e) {
//            throw new NotFoundHttpException('An error occurred while uploading the file.');
//        }
//
//        // Update the 'image' property of your entity to store the file name
//        // instead of its contents
//        $recipe->setThumbnail($newFilename);
//    }

    private function addThumbnail($thumbnail, Recipes $recipe): void
    {
        $newFileName = $this->simpleUploadService->uploadImage($thumbnail);

        // Set the new file name to the existing recipe's thumbnail property
        $recipe->setThumbnail($newFileName);
    }
    // Private method to add extra photos
    private function addExtraPhotos(array $images, Recipes $recipe): void
    {
        foreach ($images as $image) {
            $new_photos = new Photos();
            $image_new = $image['fileName'];
            $new_photo = $this->simpleUploadService->uploadImage($image_new);
            $new_photos->setFileName($new_photo);
            $recipe->addPhoto($new_photos);
        }
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                $childErrors = $this->getFormErrors($childForm);
                $errors = array_merge($errors, $childErrors);
            }
        }

        return $errors;
    }
}

