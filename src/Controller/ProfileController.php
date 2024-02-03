<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\DeleteAccountType;
use App\Repository\RecipesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/profile')]
#[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
class ProfileController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/', name: 'app_profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Your profile information is updated');

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/change-password', name: 'user_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('newPassword')->getData()));
            $entityManager->flush();

            return $this->redirectToRoute('logout');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-confirm', name: 'app_confirm_delete', methods: ['GET'])]
    public function deleteAccountConfirmation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $user = $this->getUser();
        $form = $this->createForm(DeleteAccountType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('profile/delete_account_confirmation.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->tokenStorage->setToken(null);

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
//
//    #[Route('/user-delete/{id}', name: 'app_user_delete', methods: ['GET','POST'])]
//    public function deleteAccount(
//        Request $request,
//        User $user,
//        UserRepository $userRepository,
//        RecipesRepository $recipesRepository,
//        Recipes $recipes,
//        EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//
////            foreach ($recipesRepository->findUserRecipes($user) as $recipe) {
////
////                $recipeUser = $recipe->getUser();
////
////                if ($recipeUser && $recipeUser->getId() === $user->getId()) {
////                    // Preserve user ID and username
////                    $userId = $user->getId();
////                    $username = $user->getUsername();
////
////                    // Remove sensitive user information
////                    $user->setEmail(null);
////                    $user->setPassword(null);
////
////                    // Process the recipe with preserved user ID and username
////                    $recipe->setCreatedBy($username);
////                    $recipe->setUserId($userId);
////
////                    // Persist changes to the database
////                    $entityManager->flush();
////                }
////            }
//
//            dump($recipes);
//            dump('inside if after for loop');
//            $entityManager->remove($user);
//            $entityManager->flush();
//        }
//
//        $this->tokenStorage->setToken(null);
//
//        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
//    }
}
