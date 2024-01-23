<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Cuisines;
use App\Entity\Difficulty;
use App\Entity\Ingredients;
use App\Entity\Photos;
use App\Entity\Recipes;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(RecipesCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('UMAMI Dashboard') //Dashboard Name visible to end users
            ->setTitle('<img src="images/umami-blue.png" alt="UMAMI" title="UMAMI" class="img-fluid"/>') //Include an image HTML
            ->renderContentMaximized()
            ->generateRelativeUrls()

            // by default EasyAdmin displays a black square as its default favicon;
            // use this method to display a custom favicon: the given path is passed
            // "as is" to the Twig asset() function:
            // <link rel="shortcut icon" href="{{ asset('...') }}">
            ->setFaviconPath('images/favicon.ico')
            ;
    }

    // If non ROLE_ADMIN user try to see this page Admin, it redirects to User Page
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User)
        {
            throw new \Exception('Wrong User');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl($user->getAvatarUrl())
            ->setMenuItems([
                MenuItem::LinkToUrl('My Profile', 'fas fa-user', $this->generateUrl(
                    'app_profile'
                ))
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::LinkToCrud('Recipes', 'fa-solid fa-utensils', Recipes::class);
        yield MenuItem::LinkToCrud('Cuisines', 'fa-solid fa-earth-americas', Cuisines::class);
        yield MenuItem::LinkToCrud('Categories', 'fa-solid fa-list', Categories::class);
        yield MenuItem::LinkToCrud('Difficulties', 'fa-solid fa-ranking-star', Difficulty::class);
        yield MenuItem::LinkToCrud('Photos', 'fas fa-camera', Photos::class);
        yield MenuItem::LinkToCrud('Ingredients', 'fa-solid fa-bowl-food', Ingredients::class);
        yield MenuItem::LinkToCrud('User References', 'fas fa-user', User::class);
        yield MenuItem::LinkToCrud('Comments References', 'fas fa-comment', Comments::class);
        yield MenuItem::LinkToUrl('Homepage', 'fas fa-home', $this->generateUrl('home'));
    }

    //This method allowed us to have a "Show (details)" page option button on dashboard
    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Removing delete checkboxes from dashboard to prevent deleting at once
            ->remove(Crud::PAGE_INDEX, Action::BATCH_DELETE);
    }
}
