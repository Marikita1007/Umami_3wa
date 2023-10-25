<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
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
 *
 * Controller used to manage recipes contents in the public site.
 *
 * @Route("/recipes")
 */
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

    /**
     * @Route("/list", name="list_recipes")
     */
    public function listAll(): Response
    {
        return $this->render('recipes/recipes_dashboard.html.twig', [
            'recipes' => $this->recipeRepository->findAll(),
        ]);
    }

    /**
     * Display a single recipe based on its unique identifier.
     *
     * @Route("/recipe/{id}", name="show_recipe", methods={"GET"})
     *
     */
    public function showRecipe(Recipes $recipe): Response
    {
        return $this->render('recipes/show_recipe.html.twig', ['recipe' => $recipe]);
    }

    /**
     * Controller method for creating a new recipe.
     *
     * - Authenticated users can create recipes via a recipe form.
     * - If not authenticated, it redirects to the login page with an error message.
     *
     *
     * @Route("/new", name="new_recipe", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function createNewRecipe(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {
        // Create a new Recipe entity
        $recipe = new Recipes();

        // Set user id to logged in user id
        $user = $this->getUser();

        if (!empty($user)) {
            $recipe->setUser($user); // set user entity
        } else {
            $this->addFlash('error', 'You must be logged in to create recipe.');
            return $this->redirectToRoute('/login'); // redirect user to login form
        }

        // Create a form for the Recipe entity
        $form = $this->createForm(RecipesType::class, $recipe);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the uploaded file
            $imageFile = $form['image']->getData();

            // Check if a file was uploaded
            if ($imageFile) {
                $this->saveImage($imageFile, $slugger, $recipe);
            }

            // Persist the Recipe entity
            $entityManager->persist($recipe);
            $entityManager->flush();

            // Show message of success
            $this->addFlash('success', 'Your recipe was created successfully');

            // Redirect to the recipe details page
            return $this->redirectToRoute('show_recipe', ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/new_recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_recipe", methods={"GET", "POST", "HEAD"})
     */
    public function editRecipe(EntityManagerInterface $manager, Request $request, Recipes $recipe, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RecipesType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $imageFile = $form['image']->getData();
            if ($imageFile) {
                $this->saveImage($imageFile, $slugger, $recipe);
            }

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'Your recipe is edited !');

            return $this->redirectToRoute("show_recipe", ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/edit.html.twig', [
            'form' => $form->createView(),
            //update => true shows that we use update; check form.html.twig if else !
            //'update'=> true,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_recipe")
     */
    public function deleteRecipe(EntityManagerInterface $manager, Request $request, Recipes $recipe): Response
    {
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash('success', 'Your recipe was deleted');
        return $this->redirectToRoute("list_recipes");
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

