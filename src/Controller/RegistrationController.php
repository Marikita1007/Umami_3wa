<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    //https://symfonycasts.com/screencast/symfony-security/manual-auth#play MARIKA : UserAuthenticatorInterface 追加する。
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserAuthenticatorInterface $userAuthenticator, FormLoginAuthenticator $formLoginAuthenticator): Response
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the plain password
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // 4) save the User
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

//            $signatureComponents = $verifyEmailHelperInterface->generateSignature(
//                'app_verify_email',
//                $user->getId(),
//                $user->getEmail(),
//                ['id' => $user->getId()]
//            );
//            MARIKA Remove this and replace it with codes above to verify the user by sending email
            $userAuthenticator->authenticateUser(
                $user,
                $formLoginAuthenticator,
                $request
            );

            // MARIKA : TODO: in a real app, send this as an email!
            //https://symfonycasts.com/screencast/mailer
//            $this->addFlash('success', 'Confirm your email at: '.$signatureComponents->getSignedUrl());

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/verify", name="app_verify_email")
//     */
//    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
//    {
//        $user = $userRepository->find($request->query->get('id'));
//        if (!$user) {
//            throw $this->createNotFoundException();
//        }
//
//        try {
//            $verifyEmailHelper->validateEmailConfirmation(
//                $request->getUri(),
//                $user->getId(),
//                $user->getEmail(),
//            );
//        }catch (VerifyEmailExceptionInterface $e) {
//            $this->addFlash('error', $e->getReason());
//            return $this->redirectToRoute('app_register');
//        }
//
//        $user->setIsVerified(true);
//        $entityManager->flush();
//        $this->addFlash('success', 'Account Verified! You can now log in.');
//        return $this->redirectToRoute('login');
//
//    }
//
//    /**
//     * @Route("/verify/resend", name="app_verify_resend_email")
//     */
//    public function resendVerifyEmail()
//    {
//        return $this->render('registration/resend_verify_email.html.twig');
//    }

}
