<?php

namespace App\Form;

use App\Entity\Cuisines;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuisinesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'label' => 'Search by Cuisine',
                'class' => Cuisines::class,
                'choice_label' => 'name'
            ])
//            TODO MARIKA The button below needs to be made in twig form
//            ->add('submit', SubmitType::class,[
//                'label' => 'Search',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cuisines::class,
        ]);
    }
}
