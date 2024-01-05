<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Cuisines;
use App\Entity\Difficulty;
use App\Entity\Recipes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Insert form inputs to table recipes
        $builder
            ->add('name', TextType::class, [
                'label' => 'Recipe Name'
            ])
            ->add('description', TextareaType::class,  [
                'label' => 'Description',
                'help' => 'Describe your recipe.',
            ])
            ->add('instructions', TextareaType::class,  [
                'label' => 'Instruction',
                'help' => 'Give a detailed instruction of your recipe.',
            ])
            ->add('image', FileType::class, [
                'data_class' => null, // Important: Prevents the file data from being converted to a string
                'help' => 'One image is required.',
            ])
            ->add('prep_time',IntegerType::class, [
                'required' => false,
                'label' => 'Preparation time',
                'help' => 'If your recipe doesn\'t need preparation time. Leave it blank or enter 0',
            ])
            ->add('servings',IntegerType::class, [
                'required' => false,
                'label' => 'Servings',
                'help' => 'Enter number of people can enjoy your recipe.',
            ])
            ->add('cook_time',IntegerType::class, [
                'label' => 'Cooking Time',
                'help' => 'Enter time to cook your recipe. Cooking time is mandatory.',
            ])
            ->add('calories',IntegerType::class, [
                'required' => false,
                'label' => 'Calories',
                'help' => 'Enter calories of your recipe. Leave it blank if you aren\'t sure',
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class, // Specify the Difficulty entity class
                'placeholder' => 'Select a difficulty',
                'choice_label' => 'name', // Display the 'name' property of the Difficulty entity
                'help' => 'Choose difficulty of your recipe.',
            ])
            ->add('cuisine', EntityType::class,[
                'class' => Cuisines::class,
                'placeholder' => 'Select a Cuisine',
                'choice_label' => 'name', // Display the 'name' property of the Cuisine entity
                'help' => 'Choose Cuisine of your recipe. Leave it blank if if you can\'t find your Cuisine Country.' ,
            ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'placeholder' => 'Select Categories',
                'choice_label' => 'name',
                'multiple' => true, // Allow multiple selections
                'expanded' => true, // Render as checkboxes (optional, depending on your UI preference)
                'required' => false, // Make it optional if needed
                'by_reference' => false, // Set to false to handle updates properly
                'help' => 'Choose Categories for your recipe.',
            ])
            ->add('photos', CollectionType::class,[
                'entry_type' => PhotosType::class,
                'entry_options' => ['label' => false ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
