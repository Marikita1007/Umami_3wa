
$(document).ready(function(){
    jQuery('.add-another-collection-widget').click(function (e) {
        let list = jQuery(jQuery(this).attr('data-list-selector'));
        let counter = list.data('widget-counter') || list.children().length;
        let newWidget = list.attr('data-prototype');

        newWidget = newWidget.replace(/__name__/g, counter);

        counter++;
        list.data('widget-counter', counter);

        let newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.find('input').each(function () {
            // Update the field names to match the new input
            let newName = jQuery(this).attr('name').replace(/__name__/g, counter);
            jQuery(this).attr('name', newName);
        });

        newElem.appendTo(list);
        addIngredientFormDeleteLink(newElem);

    });

    $('ul.ingredients li').each(function () {
        addIngredientFormDeleteLink($(this));
    });

    function addIngredientFormDeleteLink(item) {
        let removeFormButton = $('<button class="custom-button-small">Delete this Ingredient</button>');
        item.append(removeFormButton);

        removeFormButton.on('click', function (e) {
            e.preventDefault();
            item.remove();
        });
    }

    // Error message handler if a user try to submit form without any ingredient and an amount
    $('form').submit(function () {

        let hasNonEmptyIngredient = false;
        // Loop through all input fields with IDs starting with "ingredient_recipe_ingredients"
        $('input[id^="ingredient_recipe_ingredients"]').each(function () {
            if ($(this).val().trim() !== '') {
                hasNonEmptyIngredient = true;
            }
        });

        // Check if there are no non-empty ingredients
        if (!hasNonEmptyIngredient) {
            event.preventDefault();
            // Display an error message in an element with the ID "js-ingredient-error-message"
            $('#js-ingredient-error-message').text("Please insert at least one ingredient and its amount");
        }
    });
});

