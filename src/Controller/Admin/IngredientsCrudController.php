<?php

namespace App\Controller\Admin;

use App\Entity\Ingredients;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IngredientsCrudController extends AbstractCrudController
{
    /**
     * Get the fully-qualified class name of the managed entity.
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Ingredients::class;
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
            TextField::new('amount'),
            AssociationField::new('recipe')
                ->autocomplete(),
        ];
    }

    /**
     * Configure actions for the CRUD controller.
     * This method overrides default method and do not show the Show details button.
     *
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->disable(Action::DETAIL);
    }
}
