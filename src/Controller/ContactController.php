<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class ContactController extends AbstractController
{
    /**
     * Displays and handles the contact form submission.
     *
     * @param Request         $request  The HTTP request object.
     * @param MailerInterface $mailer   The Symfony Mailer service for sending emails.
     *
     * @return Response  A Symfony Response object rendering the contact form page or handling form submission.
     */
    #[Route('/contact/us', name: 'app_contact_us', methods: ['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // When the form is submitted and valid
            $contactFormData = $form->getData();

            $email = (new Email())
                ->from($contactFormData['email'])
                ->to('umami_admin@example.com')
                ->subject('You got mail from UMAMI contact.')
                ->text('Sender Email: '.$contactFormData['email'].\PHP_EOL.
                    'Sender Name (or Username): '.$contactFormData['name'].\PHP_EOL.
                    'Message Context : '.$contactFormData['message'],
                    'text/plain');

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent');

            return $this->redirectToRoute('app_contact_us');
        }

        return $this->render('contact_us/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
