<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\AccountNotVerifiedAuthenticationException;
use App\Security\EmailVerifier;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private EmailVerifier $emailVerifier;
    public function __construct(RouterInterface $router, EmailVerifier $emailVerifier)
    {
        $this->router = $router;
        $this->emailVerifier = $emailVerifier;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

//        if (!$user->isVerified()) {
//            throw new AccountNotVerifiedAuthenticationException();
//        }
    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        if (!$event->getException() instanceof AuthenticationException) {
            return;
        }

//        $response = new RedirectResponse(
//            $this->router->generate('app_verify_resend_email')
//        );
//
//        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        /*
         * sourcelink: https://symfonycasts.com/screencast/symfony-security/security-subscriber#codeblock-ba66da6ebd
         * Thanks to this, the user will need to enter a valid email and a valid password before our listener is called.
         */
        return [
            CheckPassportEvent::class =>  ['onCheckPassport', -10],
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    // Refactor For now leave it as it is  and don't use this mathod.
//    private function checkVerificationStatus(User $user)
//    {
//        // Check if the verification link has expired
//        // TODO MARIKA I want to update verificationRequestedAt the moment the user verify and log in to their account.
//        $verificationRequestedAt = $user->getVerificationRequestedAt();
//        if ($verificationRequestedAt !== null) {
//            if ($verificationRequestedAt > $user->getCreatedAt()->modify('+1 hour')) {
//                dump($verificationRequestedAt);
//                dump($user->getCreatedAt()->modify('+1 hour'));
////                dd('inside subsc');
//                $this->emailVerifier->sendNewVerificationEmail($user);
//                $this->router->generate('app_verify_resend_email');
//            }
//        }
//    }
}