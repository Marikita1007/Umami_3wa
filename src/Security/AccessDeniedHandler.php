<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\RouterInterface;


/**
 * AccessDeniedHandler is responsible for handling access denied situations by redirecting
 * none authorized users to the login page.
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /** @var RouterInterface $router The router service to generate URLs */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Handles the access denied situation by redirecting the user to the login page.
     *
     * @param Request $request The request object
     * @param AccessDeniedException $accessDeniedException The exception representing access denied
     *
     * @return Response A Response instance representing the redirection to the login page
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        // Redirect to the login page
        $url = $this->router->generate('login');
        return new RedirectResponse($url);
    }
}
