<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Retrieve a list of all recipes from the database.
     * This method can be called to fetch all recipes.
     *
     * @return array An array of Recipe objects representing all available recipes.
     */
    public function showRecipes()
    {
        return $this->recipeRepository->findAll();
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
     * @Route("/new", name="new_recipe.scss", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     */
    public function createNewRecipe(Request $request, EntityManagerInterface $entityManager): Response
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
            // TODO MARIKA Automatically set created_at and updated_at using Doctrine's lifecycle callbacks

            // Persist the Recipe entity
            $entityManager->persist($recipe);
            $entityManager->flush();

            // Redirect to the recipe details page
            return $this->redirectToRoute('show_recipe', ['id' => $recipe->getId()]);
        }

        return $this->render('recipes/new_recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


//TODO MARIKA Delete this code later
//namespace App\Controller;
//
//use App\Entity\Recipes;
//use App\Entity\User;
//use App\Form\RecipesType;
//use App\Repository\RecipesRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\User\UserInterface;
//
//#[Route('/recipes')]
//class RecipesController extends AbstractController
//{
//
//    #[Route('/', name: 'recipes_index', methods: ['GET'])]
//    public function showAllRecipes(RecipesRepository $recipesRepository): Response
//    {
//        return $this->render('recipes/index.html.twig', [
//            'recipes' => $recipesRepository->findAll(),
//        ]);
//    }
//
//    #[Route('/new', name: 'recipes_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $recipe = new Recipes();
//        $form = $this->createForm(RecipesType::class, $recipe);
//
//        // Process the form
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//
//            //TODO MARIKA FIX THIS !!
//            $user = $this->user(); // Get login user TODO GET MORE EXPLANATIONS
//
//            if ($user instanceof UserInterface) {
//                $recipe->setUser($user); // Set user infos to $recipe
//            } else {
//                // TODO MARIKA ユーザーがログインしていない場合の処理
//            }
//
//
//            $entityManager->persist($recipe);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('recipes/new_recipe.html.twig', [
//            'recipe' => $recipe,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'recipes_show', methods: ['GET'])]
//    public function show(Recipes $recipe): Response
//    {
//        return $this->render('recipes/show_recipe.html.twig', [
//            'recipe' => $recipe,
//        ]);
//    }
//
//    #[Route('/{id}/edit', name: 'recipes_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Recipes $recipe, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(RecipesType::class, $recipe);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('recipes/edit.html.twig', [
//            'recipe' => $recipe,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'recipes_delete', methods: ['POST'])]
//    public function delete(Request $request, Recipes $recipe, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($recipe);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
//    }
//}
