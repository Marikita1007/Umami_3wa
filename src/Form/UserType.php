<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'Username',
                'disabled' => true, //Users can't change username once it's chosen.
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preFillUsername'])
            ->add('email', EmailType::class,[
                'label' => 'Email',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preFillEmail'])
        ;
    }

    public function preFillEmail(FormEvent $event): void
    {
        $user = $event->getData();

        if($user instanceof User)
        {
            $event->getForm()->get('email')->setData($user->getEmail());
        }
    }

    public function preFillUsername(FormEvent $event): void
    {
        $user = $event->getData();

        if($user instanceof User)
        {
            $event->getForm()->get('username')->setData($user->getUsername());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
