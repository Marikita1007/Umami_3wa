<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Controller managing user authentication and logout.
 */
class SecurityController extends AbstractController
{
    use TargetPathTrait;

    /**
     * Handles user login.
     *
     * @param AuthenticationUtils $authenticationUtils The Symfony AuthenticationUtils service.
     *
     * @return Response  A Symfony Response object rendering the login page.
     */
    #[Route("/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $errors = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('security/security.html.twig', [
            'error' => $errors,
            'last_username' =>$lastUserName
        ]);
    }

    /**
     * Handles user logout.
     *
     * @throws \Exception
     */
    #[Route("/logout", name: "logout")]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

}
