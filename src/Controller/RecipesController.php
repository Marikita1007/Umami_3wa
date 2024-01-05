<?php

namespace App\Controller;

use App\Entity\Comments;
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
use App\Repository\IngredientsRepository;
use App\Repository\PhotosRepository;
use App\Repository\RecipesRepository;
use App\Services\SimpleUploadService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            ['created_at' => 'ASC'], // Order by creation date in descending order
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
    ): Response
    {
        //Add comments to the recipe
        $comments = new Comments();

        $form = $this->createForm(CommentsType::class, $comments);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Set the user for the comment
            $comments->setUser($this->getUser());

            // Set the recipe for the comment
            $comments->setRecipe($recipe);

            $recipe->addComment($comments);
            $entityManager->persist($comments);
            $entityManager->flush();

            //TODO MARIKA FIX THIS and make sure that it appears while AJAX call
            $this->addFlash('success', 'Your comment is registered.');

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
            'extraPhotos' => $photosRepository->findBy(['recipe' => $recipe]),
            'form' => $form->createView(),
            // Fetch comments with associated user information
            'comments' => $commentsRepository->findBy(['recipe' => $recipe], ['datetime' => 'ASC']),
            'categories' => $recipe->getCategory(), // Fetch categories associated with the recipe
        ]);
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
    public function createNewRecipeAndIngredients(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        SimpleUploadService $simpleUploadService,
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

            // Get additional photos from Photos Entity
            $photos = $request->files->all();

            if ($photos == null){
                //TODO Change this Later cause User already added one photo 29122023
                $this->addFlash('danger', 'Each product must have at least one photo');
                return $this->redirectToRoute('new_recipe');
            } else {
                $images = $photos['ingredient_recipe']['recipe']['photos'] ?? null;
                if ($images) {
                    $this->addExtraPhotos($images, $recipe);
                }
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
        SluggerInterface $slugger,
        SimpleUploadService $simpleUploadService,
        PhotosRepository $photosRepository): Response
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

            //Get additional photos
            $photos = $request->files->all();

            if ($photos === null){
                //TODO MARIKA Change here cause user already have one image
                $this->addFlash('danger', 'Each product must have at least one photo');
                return $this->redirectToRoute('edit_recipe' , ['id' => $recipe->getId()]);
            } else {
                $images = $photos['ingredient_recipe']['recipe']['photos'] ?? null;
                if ($images !== null) {
                    $this->addExtraPhotos($images, $recipe);
                }
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recipe and ingredients have been updated.');

            return $this->redirectToRoute("show_recipe", ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
            'photos' => $photosRepository->findBy(['recipe' => $recipe]),
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

        $formCuisines = $this->createForm(CuisinesType::class);
        $formCuisines->handleRequest($request);

        $formCategories = $this->createForm(CategoriesType::class);
        $formCategories->handleRequest($request);

        if ($formFilterSearch->isSubmitted() && $formFilterSearch->isValid()){
            $data = $formFilterSearch->getData();
            $word = $data['word'];

            $category = $formCuisines->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByWord' => $recipesRepository->getByName($word),
                'formFilterSearch' => $formFilterSearch->createView(),
                'formCuisines' => $formCuisines->createView(),
                'formCategories' => $formCategories->createView(),
            ]);
        }

        if ($formCuisines->isSubmitted() && $formCuisines->isSubmitted() ){
            $data = $formCuisines->getData();
            $category = $formCuisines->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'recipesByCuisine' => $recipesRepository->findByCuisine($category),
                'formCuisines' => $formCuisines->createView(),
                'formFilterSearch' => $formFilterSearch->createView(),
                'formCategories' => $formCategories->createView(),
            ]);
        }

        if ($formCategories->isSubmitted() && $formCategories->isSubmitted() ){
            $data = $formCategories->getData();
            $category = $formCategories->get('name')->getData();

            return $this->render('recipes/recipes_filters.html.twig', [
                'formCuisines' => $formCuisines->createView(),
                'formCategories' => $formCategories->createView(),
                'formFilterSearch' => $formFilterSearch->createView(),
            ]);
        }

        return $this->render('recipes/recipes_filters.html.twig', [
            'recipes' => $recipesRepository->findAll(),
            'formFilterSearch' => $formFilterSearch->createView(),
            'formCuisines' => $formCuisines->createView(),
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

        //TODO MARIKA This is for AJAX call one 30122023 Add it later if needed
//        $data = json_decode($request->getContent(), true);
//
//        if($this->isCsrfTokenValid("delete" . $photos->getId(), $data['_token']))
//        {
//            $photo_name = $photos->getName();
//
//            if($simpleUploadService->deleteImage($photo_name))
//            {
//                $entityManager->remove($photos);
//
//                $entityManager->flush();
//
//                $this->addFlash('success', 'Your photo is successfully deleted');
//                return new JsonResponse(['success' => 'Your photo is successfully deleted'], 200);
//            }
//        }
//        return new JsonResponse(['error' => 'Invalid Token'], 400);
    }

    //TODO MARIKA Use this method on CRUD new Photos too
    // Private method to add extra photos
    private function addExtraPhotos(array $images, Recipes $recipe): void
    {
        foreach ($images as $image) {
            $new_photos = new Photos();
            $image_new = $image['name'];
            $new_photo = $this->simpleUploadService->uploadImage($image_new);
            $new_photos->setName($new_photo);
            // Set the bidirectional association
//            $new_photos->setRecipes($recipe);
            $recipe->addPhoto($new_photos);
        }
    }
}

