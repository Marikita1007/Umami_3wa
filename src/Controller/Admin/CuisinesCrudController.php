<?php

namespace App\Controller\Admin;

use App\Entity\Cuisines;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Annotation\Route;

class CuisinesCrudController extends AbstractCrudController
{
    /**
     * Get the fully-qualified class name of the managed entity.
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Cuisines::class;
    }

    /**
     * Configure CRUD settings.
     *
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']); // Sort by id in descending order
    }

    /**
     * Configure fields for the CRUD controller.
     *
     * @param string $pageName
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
        ];
    }

}
