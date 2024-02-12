<?php

namespace App\Controller\Admin;

use App\Entity\Difficulty;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DifficultyCrudController extends AbstractCrudController
{
    /**
     * Get the fully-qualified class name of the managed entity.
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Difficulty::class;
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
