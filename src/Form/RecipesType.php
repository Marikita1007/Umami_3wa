<?php

namespace App\Form;

use App\Entity\Recipes;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Insert form inputs to table recipes
        // TODO MARIKA Edit this insert to make it more detailed
        $builder
            ->add('name')
            ->add('description')
            ->add('instructions')
            ->add('created_at')
            ->add('updated_at')
            ->add('image')
            ->add('prep_time')
            ->add('servings')
            ->add('cook_time')
            ->add('calories')
            ->add('difficulty')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
