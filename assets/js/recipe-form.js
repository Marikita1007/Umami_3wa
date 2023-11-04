
    $(document).ready(function(){
        jQuery('.add-another-collection-widget').click(function (e) {
            var list = jQuery(jQuery(this).attr('data-list-selector'));
            var counter = list.data('widget-counter') || list.children().length;
            var newWidget = list.attr('data-prototype');

            newWidget = newWidget.replace(/__name__/g, counter);

            counter++;
            list.data('widget-counter', counter);

            var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
            newElem.find('input').each(function () {
                // Update the field names to match the new input
                var newName = jQuery(this).attr('name').replace(/__name__/g, counter);
                jQuery(this).attr('name', newName);
            });

            newElem.appendTo(list);
            addIngredientFormDeleteLink(newElem);
        });

        $('ul.ingredients li').each(function () {
            addIngredientFormDeleteLink($(this));
        });

        function addIngredientFormDeleteLink(item) {
            var removeFormButton = $('<button class="custom-button-small">Delete this Ingredient</button>');
            item.append(removeFormButton);

            removeFormButton.on('click', function (e) {
                e.preventDefault();
                item.remove();
            });
        }
    });
