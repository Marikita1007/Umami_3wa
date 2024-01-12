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
    #[Route('/contact/us', name: 'app_contact_us')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        //TODO MARIKA Ask teacher or Jennifer why this contact is not working
        if($form->isSubmitted() && $form->isValid()) {

            // When the form is submitted and valid
            $contactFormData = $form->getData();

            $email = (new Email())
                ->from($contactFormData['email'])
                ->to('umami_admin@example.com')
                ->subject('You got mail.')
                ->text('Sender : '.$contactFormData['email'].\PHP_EOL.
                    $contactFormData['Message'],
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