<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for displaying legal rights and policies.
 */
class LegalRightsController extends AbstractController
{
    /**
     * Displays the privacy policy page.
     *
     * @return Response  A Symfony Response object rendering the privacy policy page.
     */
    #[Route('/legal/privacy-policy', name: 'privacy_policy')]
    public function showPrivacyPolicy(): Response
    {
        return $this->render('legal_rights/privacy_policy.html.twig', [
        ]);
    }

    /**
     * Displays the legal notice page.
     *
     * @return Response  A Symfony Response object rendering the legal notice page.
     */
    #[Route('/legal/legal-notice', name: 'legal_notice')]
    public function showLegalNotice(): Response
    {
        return $this->render('legal_rights/legal_notice.html.twig', [
        ]);
    }
}
