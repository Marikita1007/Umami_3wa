<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Form for Category Filter
class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'label' => 'Search by Category', // Set the label for the form field
                'class' => Categories::class, // Specify the entity class for the form field
                'choice_label' => 'name', // Define the property of the entity to use as the choice label
                'attr' => [ // Set attributes for the form field
                    'id' => 'category-select', // Set the 'id' attribute for the form field
                    'aria-label' => 'Category', // Add an accessible label for the form fiel
                ],
                'label_attr' => [ // Set attributes for the label associated with the form field
                    'for' => 'category-select', // Use the 'id' attribute of the form field as the 'for' attribute of the label
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
            'validation_groups' => false, // Stop constrains check for filter search
        ]);
    }
}
