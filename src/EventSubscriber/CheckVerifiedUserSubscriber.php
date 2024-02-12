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

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;

    /**
     * CheckVerifiedUserSubscriber constructor.
     *
     * @param RouterInterface $router       The Symfony router service.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Handles the 'CheckPassport' event and ensures the user is of the expected type.
     *
     * @param CheckPassportEvent $event The CheckPassportEvent event.
     *
     * @throws \Exception If the passport or user type is unexpected.
     */
    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();

        // Ensure the passport is the expected type
        if (!$passport instanceof UserPassportInterface) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();

        // Ensure the user is the expected type
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

//        if (!$user->isVerified()) {
//            throw new AccountNotVerifiedAuthenticationException();
//        }
    }

    /**
     * Handles the 'LoginFailure' event and redirects to the login page on authentication failure.
     *
     * @param LoginFailureEvent $event The LoginFailureEvent event.
     */
    public function onLoginFailure(LoginFailureEvent $event)
    {
        // Check if the exception in the event is an AuthenticationException
        if (!$event->getException() instanceof AuthenticationException) {
            return;
        }

        // Redirect to the login page on authentication failure
        $response = new RedirectResponse(
            $this->router->generate('login')
        );

        $event->setResponse($response);
    }

    /**
     * Specifies the events to which this subscriber listens and their corresponding methods.
     *
     * @return array An array of subscribed events and their corresponding methods.
     */
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
}