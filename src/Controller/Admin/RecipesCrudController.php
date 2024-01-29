<?php

namespace App\Controller\Admin;

use App\Entity\Recipes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
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
use Symfony\Component\Validator\Constraints as Assert;

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
            ImageField::new('thumbnail', 'Thumbnail Image')
                ->setBasePath('/uploads/images') // Base path when displaying images
                ->setUploadDir('public/uploads/images')// upload directory
                ->setHelp('Only .png and .jpg')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setFormTypeOption('constraints', [
                    new  \App\Validator\Constraints\EasyAdminFile([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image. '
                    ])
                ]),
            TextareaField::new('description', 'Description')
                ->renderAsHtml()
                ->hideOnIndex(),
            TextareaField::new('instructions', 'Instructions')
                ->renderAsHtml()
                ->hideOnIndex(),
            IntegerField::new('prepTime', 'Preparation Time')
                ->hideOnIndex(),
            IntegerField::new('servings', 'Servings')
                ->hideOnIndex(),
            IntegerField::new('cookTime', 'Cooking Time'),
            IntegerField::new('calories', 'Calories')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
            DateTimeField::new('updatedAt')
                ->hideOnForm(),
            AssociationField::new('cuisine', 'Cuisine')
                ->setRequired(true) //This field is mandatory
                ->autocomplete(),
            // Use AssociationField for the ManyToMany relationship with Categories
            AssociationField::new('category', 'Categories')
                ->setRequired(true) //This field is mandatory
                ->autocomplete() // Use autocomplete for a better user experience
                ->setFormTypeOptions([
                    'by_reference' => false, // Set to false to handle updates properly
                ]),
            AssociationField::new('difficulty', 'Difficulty of cooking')
                ->setRequired(true) //This field is mandatory
                ->autocomplete(),
            AssociationField::new('user', 'Recipe Created User')
                ->setRequired(true) //This field is mandatory,
        ];
    }
}
