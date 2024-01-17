// Ajax to add like to each recipe
$(document).ready(function () {
    $('.like-icon').on('click', function () {
        var $icon = $(this);
        var recipeId = $icon.data('recipe-id');

        $.ajax({
            url: '/recipes/' + recipeId + '/like',
            method: 'POST'
        }).then(function (data) {
            // Toggle the heart icon based on the response
            if (data.liked) {
                $icon.removeClass('fa-regular').addClass('fa-solid').css('color', 'red');
            } else {
                $icon.removeClass('fa-solid').addClass('fa-regular').css('color', ''); //Reset to default color
            }
        });
    });
});