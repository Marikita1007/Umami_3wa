<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Email Address',
                    'required' => 'required',
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Username: (Username can\'t be changed)',
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Username',
                    'required' => 'required',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Password:',
                'mapped' => false,
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Password',
                    'required' => 'required',
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Password must not be blank']),
                    new Assert\Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/",
                        'message' => 'Your password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirm Password:',
                'attr' => [
                    'class' => 'input-text',
                    'placeholder' => 'Confirm Password',
                    'required' => 'required',
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please confirm your password',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
