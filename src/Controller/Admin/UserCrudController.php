<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, EmailField, TextField};
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormEvents};


class UserCrudController extends AbstractCrudController
{
    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher for user entities.
     */
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}

    /**
     * Gets the fully-qualified class name of the User entity.
     *
     * @return string The fully-qualified class name.
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Configures the CRUD operations for the User entity.
     *
     * @param Crud $crud The CRUD configuration object.
     *
     * @return Crud The modified CRUD configuration.
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']); // Sort by id in descending order
    }

    /**
     * Configures the fields of the User entity for different pages in the EasyAdmin CRUD interface.
     *
     * @param string $pageName The name of the current page (e.g., 'index', 'detail', 'new', 'edit').
     *
     * @return iterable An array of field configurations.
     */
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            EmailField::new('email'),
            ChoiceField::new('roles')
                ->setChoices([
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_USER' => 'ROLE_USER',
                ])
                ->allowMultipleChoices()
                ->renderAsBadges(),
            BooleanField::new('isVerified')
                ->renderAsSwitch(false),
        ];

        $password = TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => '(Repeat)'],
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms()
        ;
        $fields[] = $password;

        return $fields;
    }

    /**
     * Creates a new form builder for the 'User' entity when creating a new record.
     *
     * @param EntityDto     $entityDto   The DTO of the entity.
     * @param KeyValueStore $formOptions Options for building the form.
     * @param AdminContext  $context     The context of the EasyAdmin application.
     *
     * @return FormBuilderInterface The configured form builder.
     */
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    /**
     * Creates a new form builder for the 'User' entity when editing an existing record.
     *
     * @param EntityDto     $entityDto   The DTO of the entity.
     * @param KeyValueStore $formOptions Options for building the form.
     * @param AdminContext  $context     The context of the EasyAdmin application.
     *
     * @return FormBuilderInterface The configured form builder.
     */
    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    /**
     * Adds a password change event listener to the form builder.
     *
     * @param FormBuilderInterface $formBuilder The form builder to which the listener is added.
     *
     * @return FormBuilderInterface The form builder with the added event listener.
     */
    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    /**
     * Generates a closure event listener for hashing the password.
     */
    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }
}
