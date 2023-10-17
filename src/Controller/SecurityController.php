<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
//MARIKA TODO This page is copy of Security COntroller from Base. Edit it to MINE.
class SecurityController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // if user is already logged in, don't display the login page again
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        /**
         * MARIKA
         * This statement solves an edge-case: if you change the locale in the login
         * page, after a successful login you are redirected to a page in the previous
         * locale. This code regenerates the referrer URL whenever the login page is
         * browsed, to ensure that its locale is always the current one.
         */
        $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('home'));

        return $this->render('security/security.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $authenticationUtils->getLastUsername(),
            // last authentication error (if any)
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * MARIKA
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in config/packages/security.yaml
     */
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

}
