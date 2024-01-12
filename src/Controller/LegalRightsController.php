<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalRightsController extends AbstractController
{
    #[Route('/legal/privacy-policy', name: 'privacy_policy')]
    public function showPrivacyPolicy(): Response
    {
        return $this->render('legal_rights/privacy_policy.html.twig', [
        ]);
    }

    #[Route('/legal/legal-notice', name: 'legal_notice')]
    public function showLegalNotice(): Response
    {
        return $this->render('legal_rights/legal_notice.html.twig', [
        ]);
    }
}
