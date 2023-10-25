<?php

namespace App\Form;

use App\Entity\Difficulty;
use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Insert form inputs to table recipes
        // TODO MARIKA Edit this insert to make it more detailed
        $builder
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('description')
            ->add('instructions')
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null, // Important: Prevents the file data from being converted to a string
            ])
            ->add('prep_time')
            ->add('servings')
            ->add('cook_time')
            ->add('calories')

            //TODO MARIKA 22102023 Update difficulty form to insert to difficulty table
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class, // Specify the Difficulty entity class
                'placeholder' => 'Select a difficulty',
                'choice_label' => 'name', // Display the 'name' property of the Difficulty entity
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
