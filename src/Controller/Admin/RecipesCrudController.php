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
            TextField::new('name', 'Name'),
            ImageField::new('image', 'Thumbnail Image')
                ->setBasePath('/uploads/images') // Base path when displaying images
                ->setUploadDir('public/uploads/images'), // upload directory
            TextareaField::new('description', 'Description')
                ->renderAsHtml()
                ->hideOnIndex(),
            TextareaField::new('instructions', 'Instructions')
                ->renderAsHtml()
                ->hideOnIndex(),
            IntegerField::new('prep_time', 'Preparation Time')
                ->hideOnIndex(),
            IntegerField::new('servings', 'Servings')
                ->hideOnIndex(),
            IntegerField::new('cook_time', 'Cooking Time'),
            IntegerField::new('calories', 'Calories')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
            DateTimeField::new('updatedAt')//TODO MARIKA THIS updated at need to be updated autolatically
                ->hideOnForm(),
            AssociationField::new('cuisine', 'Cuisine')
                ->autocomplete(),
            // Use AssociationField for the ManyToMany relationship with Categories
            AssociationField::new('category', 'Categories')
                ->setRequired(true) // Adjust as needed
                ->autocomplete() // Use autocomplete for a better user experience
                ->setFormTypeOptions([
                    'by_reference' => false, // Set to false to handle updates properly
                ]),
            AssociationField::new('difficulty', 'Difficulty of cooking')
                ->autocomplete(),
            AssociationField::new('user', 'Recipe Created User'),
        ];
    }
}
