<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
Use Symfony\Component\Mime\Email;

//Refactor
//TODO MARIKA Delete this if I have time move mail system here and call this class
class MailerService
{
//    public function __construct (private MailerInterface $mailer) {
//
//    }
//
//    public function sendEmail(
//        $to = 'siteadmin@hotmail.fr',
//        $subject = 'This is the Mail subject !',
//        $content = '',
//        $text = ''
//    ): void{
//        $email = (new Email())
//            ->from('noreply@mysite.com')
//            ->to($to)
//            ->subject($subject)
//            ->text($text)
//            ->html($content);
//        $this->mailer->send($email);
//    }
}