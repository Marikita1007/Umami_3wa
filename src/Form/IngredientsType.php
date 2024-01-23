<?php

namespace App\Form;

use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ingredient Name',
                'help' => 'Insert at least one ingredient.',
                'attr' => [
                    'aria-label' => 'Ingredient Name',
                ],
            ])
            ->add('amount', TextType::class, [
                'label' => 'Amount of Ingredient',
                'help' => 'Insert amount of the ingredient.',
                'attr' => [
                    'aria-label' => 'Amount of Ingredient',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredients::class,
        ]);
    }
}
