<?php

namespace App\EventSubscriber;
use App\Entity\User;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

//TODO MARIKA Check if I still use this
class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        // MARIKA this check makes sure that our Passport has a getUser() method, which in practice, it always will.
        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

//        if (!$user->getIsVerified()) {
//            throw new AccountNotVerifiedAuthenticationException();
//        }
    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        if (!$event->getException() instanceof AccountNotVerifiedAuthenticationException) {
            return;
        }

        $response = new RedirectResponse(
            $this->router->generate('app_verify_resend_email')
        );
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        // MARIKA : https://symfonycasts.com/screencast/symfony-security/security-subscriber#codeblock-ba66da6ebd
        // Thanks to this, the user will need to
        // enter a valid email and a valid password before our listener is called.
        return [
            CheckPassportEvent::class =>  ['onCheckPassport', -10],
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }
}