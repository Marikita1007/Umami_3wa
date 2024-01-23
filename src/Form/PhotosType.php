<?php

namespace App\Form;

use App\Entity\Photos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PhotosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName', FileType::class,[
                'label' => false,
                'help' => 'Enter extra photos if you have some.',
                'required' => false,
                'constraints' => [ // Write constraint on form type to prevent wrong file types from js
                    new Assert\Image([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/png', 'image/jpeg'],
                        'mimeTypesMessage' => 'Please upload a valid PNG or JPEG image.',
                    ]),
                ],
                'attr' => [
                    'aria-label' => 'Additional Photos',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photos::class,
        ]);
    }
}
