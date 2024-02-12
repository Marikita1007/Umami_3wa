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
    private Environment $twig;

    /**
     * PageNotFoundListener constructor.
     *
     * @param Environment $twig The Twig environment for rendering templates.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Handles kernel exceptions, specifically targeting NotFoundHttpException.
     *
     * @param ExceptionEvent $event The kernel exception event.
     */
    public function onKernelException(ExceptionEvent $event):void
    {
        //  Get the thrown exception
        $exception = $event->getThrowable();

        // Check if the exception is a NotFoundHttpException
        if(!$exception instanceof NotFoundHttpException)
        {
            // If not, do nothing and let other listeners/handlers handle it
            return;
        }

        // Render the standard 404 page
        $content = $this->twig->render('notification/page_not_found_exception.html.twig');
        // Set the rendered content as the response for the event
        $event->setResponse((new Response())->setContent($content));
    }
}