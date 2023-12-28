<?php

namespace App\Controller\Admin;

use App\Entity\Recipes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']); // Sort by id in descending order
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('name'),
            ImageField::new('image', 'Image')
                ->setBasePath('/uploads/images') // Base path when displaying images
                ->setUploadDir('public/uploads/images'), // upload directory
            TextareaField::new('description', 'Description')
                ->renderAsHtml()
                ->hideOnIndex(),
            TextareaField::new('instructions', 'Instructions')
                ->renderAsHtml()
                ->hideOnIndex(),
            IntegerField::new('prep_time'),
            IntegerField::new('servings')
                ->hideOnIndex(),
            IntegerField::new('cook_time')
                ->hideOnIndex(),
            IntegerField::new('calories')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
            DateTimeField::new('updatedAt')//TODO MARIKA THIS updated at need to be updated autolatically
                ->hideOnForm(),
            AssociationField::new('cuisine')
                ->autocomplete(),
            AssociationField::new('difficulty')
                ->autocomplete(),
            AssociationField::new('user'),
        ];
    }
}
