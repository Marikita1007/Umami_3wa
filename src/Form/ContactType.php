<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Email field with client-side validation and server-side constraints
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Email Address',
                    'aria-label' => 'Email Address',
                ],
                'constraints' => [
                    new Assert\Email(['message' => 'Please enter a valid email address.']), // Validates email format
                    new Assert\NotBlank(['message' => 'Email is required.']), // Ensures the field is not empty on the server side
                ],
            ])
            // Name field with client-side placeholder and server-side constraint
            ->add('name',TextType::class, [
                'label' => 'Name (Optional: Username if you have an account)',
                'attr' => [
                    'placeholder' => 'Name',
                    'aria-label' => 'Name',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Name is required.']),
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-z\p{L}\p{N}!?\s;.]+$/u',
                        'message' => 'Invalid characters in the name field.',
                    ]),
                ],
            ])
            // Message field with client-side rows attribute and server-side constraints
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'rows' => 5, // Sets the number of visible text lines on the client side
                    'aria-label' => 'Message',
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Your message must be at least {{ limit }} characters long.',
                    ]),
                    new NotBlank(['message' => 'Message is required.']),
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-z\p{L}\p{N}!?\s;.]+$/u',
                        'message' => 'Invalid characters in the message field.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
