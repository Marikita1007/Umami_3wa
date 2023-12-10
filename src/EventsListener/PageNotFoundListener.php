<?php

namespace App\EventsListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class PageNotFoundListener
{
    public function __construct(private Environment $twig){}

    public function onKernelException(ExceptionEvent $event):void
    {
        $exception = $event->getThrowable();

        if(!$exception instanceof NotFoundHttpException)
        {
            return;
        }

        $content = $this->twig->render('notification/page_not_found_exception.html.twig');
        $event->setResponse((new Response())->setContent($content));

    }

}