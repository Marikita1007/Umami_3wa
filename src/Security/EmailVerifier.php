<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;


/**
 * The EmailVerifier class is responsible for handling email verification related tasks,
 * such as sending confirmation emails, handling email confirmation, and sending new verification emails.
 */
class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    /**
     * EmailVerifier constructor.
     *
     * @param VerifyEmailHelperInterface $helper The service for generating and validating email verification signatures
     * @param MailerInterface $mailer The mailer service used for sending emails
     * @param EntityManagerInterface $manager The entity manager for managing user entities
     * @param RouterInterface $router The router service for generating URLs
     */
    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager, RouterInterface $router)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
        $this->router = $router;
    }

    /**
     * Sends an email confirmation to the user upon registration.
     *
     * @param string $verifyEmailRouteName The name of the route for email verification
     * @param UserInterface $user The user to send the confirmation email to
     * @param TemplatedEmail $email The templated email containing the confirmation message
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName, //This calls app_verify_email
            $user->getId(),
            $user->getEmail(),
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * Handles the email confirmation process and updates the user's verification status.
     *
     * @param Request $request The request containing the confirmation URL
     * @param UserInterface $user The user whose email is being confirmed
     *
     * @throws VerifyEmailExceptionInterface Thrown if the email confirmation fails
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Sends a new verification email to the user when the previous confirmation is expired.
     *
     * @param UserInterface $user The user to send the new verification email to
     */
    public function sendNewVerificationEmail(UserInterface $user): void
    {
        $this->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('umami_admin@example.com', 'Admin Umami'))
                ->to($user->getEmail())
                ->subject('Your previous confirmation Email is expired.  Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
