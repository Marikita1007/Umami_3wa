<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

//MARIKA https://symfonycasts.com/screencast/symfony-security/form-login-options#play
//このショーから使ってないかも
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    private UserRepository $userRepository;
    private RouterInterface $routerInterface;
    public function __construct(UserRepository $userRepository, RouterInterface $routerInterface)
    {
        $this->userRepository = $userRepository;
        $this->routerInterface = $routerInterface;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($email, function($userIdentifier) {
                // optionally pass a callback to load the User manually
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    throw new UserNotFoundException();
                }
                return $user;
            }),
            // MARIKA https://symfonycasts.com/screencast/symfony-security/csrf-token#play
            new PasswordCredentials($password),
            [
                //MARIKA TODO MAke sure that I understand how this csrf token will be created
                new CsrfTokenBadge(
                    'authenticate',
                    $request->request->get('_csrf_token')
                ),
                (new RememberMeBadge())->enable(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // MARIKA  This line does two things at once: it sets a $target variable to the target
        // path and, in the if statement, checks to see if this has something in it. Because,
        // if the user goes directly to the login page, then they won't have a target path in the session.
        if ($target = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($target);
        }

        return new RedirectResponse(
            $this->routerInterface->generate('home')
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        //MARIKA : https://symfonycasts.com/screencast/symfony-security/abstract-login-form-authenticator#play
        return $this->routerInterface->generate('login');
    }
}
