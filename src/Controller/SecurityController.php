<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SecurityController extends AbstractController
{
    use TargetPathTrait;

    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

// TODO MARIKA Check if I still need this code or not<
//        // If user is already logged in, don't display the login page again
//        if ($this->getUser()) {
//            return $this->redirectToRoute('home');
//        }
//
//        /**
//         * MARIKA
//         * This statement solves an edge-case: if you change the locale in the login
//         * page, after a successful login you are redirected to a page in the previous
//         * locale. This code regenerates the referrer URL whenever the login page is
//         * browsed, to ensure that its locale is always the current one.
//         */
//        $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('home'));
//
//        return $this->render('security/security.html.twig', [
//            // last username entered by the user (if any)
//            'last_username' => $authenticationUtils->getLastUsername(),
//            // last authentication error (if any)
//            'error' => $authenticationUtils->getLastAuthenticationError(),
//        ]);

        $errors = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('security/security.html.twig', [
            'error' => $errors,
            'last_username' =>$lastUserName
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route("/logout", name: "logout")]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

}
