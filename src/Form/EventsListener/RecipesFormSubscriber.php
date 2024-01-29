<?php

namespace App\Form\EventsListener;

use App\Entity\Recipes;
use App\Form\IngredientRecipeType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customizes the Recipes form to pre-fill the thumbnail field when updating a recipe.
 */
class RecipesFormSubscriber implements EventSubscriberInterface
{
    // Define the subscribed events
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    // This method is triggered before the form is populated with data
    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Check if the form's parent is IngredientsRecipeType
        if ($form->getParent() && $form->getParent()->getConfig()->getType()->getInnerType() instanceof IngredientRecipeType) {

            // Check if $data is an instance of Recipes (existing recipe) or null (new recipe)
            if ($data instanceof Recipes) {

                // Check if the recipe has a current_thumbnail
                $currentThumbnail = $data->getThumbnail(); // Assuming the property is named "thumbnail"

                if ($currentThumbnail) {
                    // Add the current_thumbnail to the options of the thumbnail field in RecipesType
                    $form->add('thumbnail', FileType::class, [
                        'label' => 'Thumbnail',
                        'mapped' => false,
                        'data' => $currentThumbnail,
                        'data_class' => null,
                        'help' => 'One thumbnail is required.',
                        'required' => false,
                        'constraints' => [
                            new Assert\Image([
                                'maxSize' => '5M',
                                'mimeTypes' => ['image/png', 'image/jpeg'],
                                'mimeTypesMessage' => 'Please upload a valid PNG or JPEG thumbnail image.',
                            ]),
                        ],
                        'attr' => [
                            'aria-label' => 'Recipe thumbnail Image',
                        ],
                    ]);
                }
            }
        }
    }
}
