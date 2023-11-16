// TODO Make sure responseJSONis working !!
// Comments everywhere
// Create Form Constraint

$(document).ready(function(){
    showAllCuisines();

    $("#createCuisinesButton").click(createCuisines);

    // Event delegation for edit buttons
    $("#js-cuisines-table-body").on("click", ".edit-button", function() {
        let cuisineId = $(this).data("cuisine-id");
        editCuisine(cuisineId);
    });

    // Event delegation for delete buttons
    $("#js-cuisines-table-body").on("click", ".delete-button", function() {
        let cuisineId = $(this).data("cuisine-id");
        destroyCuisine(cuisineId);
    });
});

/*
This function will get all the cuisines
*/
function showAllCuisines()
{
    $.ajax({
        url: "/cuisines/list",
        method: "GET",
        success: function(response) {
            $("#js-cuisines-table-body").html("");
            let cuisines = response;
            for (let i = 0; i < cuisines.length; i++)
            {
                let editBtn =  '<button ' +
                    ' class="edit-button custom-button-icon" ' +
                    ' data-cuisine-id="' + cuisines[i].id + '"><i class="fas fa-edit"></i>' +
                    '</button> ';
                let deleteBtn =  '<button ' +
                    ' class="delete-button custom-button-icon" ' +
                    ' data-cuisine-id="' + cuisines[i].id + '"><i class="fas fa-trash-alt"></i>' +
                    '</button>';

                let cuisineRow = '<tr>' +
                    '<td>' + cuisines[i].id + '</td>' +
                    '<td>' + cuisines[i].name + '</td>' +
                    '<td>' + editBtn + deleteBtn + '</td>' +
                    '</tr>';
                $("#js-cuisines-table-body").append(cuisineRow);
            }
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
}

/*
check if form submitted is for creating or updating
*/
$("#js-save-cuisine-button").click(function(event){
    event.preventDefault();
    if($("#update_id").val() == null || $("#update_id").val() == "")
    {
        storeCuisine();
    } else {
        updateCuisine();
    }
})

/* Add an event listener to close the modal when the close button is clicked */
$("#form-modal button[aria-label='Close']").click(function () {
    $("#form-modal").removeClass("show");
    $(".modal-overlay").removeClass("show");
});

/*
edit record function
it will get the existing value and show the cuisine form
*/
function editCuisine(id)
{
    $.ajax({
        url: "/cuisines/show/" + id,
        method: "GET",
        success: function(response) {
            let cuisine = response
            $("#alert-div").html("");
            $("#error-div").html("");

            // Set values in the form for editing
            $("#update_id").val(cuisine.id);
            $("#name").val(cuisine.name);

            // Show the modal
            $("#form-modal").addClass("show");
            $(".modal-overlay").addClass("show");
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
}

/*
submit the form and will be stored to the database
*/
function storeCuisine()
{
    $("#js-save-cuisine-button").prop('disabled', true);
    let data = {
        name: $("#name").val(),
    };
    $.ajax({
        url: "/cuisines/new",
        method: "POST",
        data: data,
        success: function(response) {
            $("#js-save-cuisine-button").prop('disabled', false);
            let successHtml = '<div class="alert-success-message" role="alert"><b>New Cuisine Name Created Successfully</b></div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            showAllCuisines();
            $("#form-modal").removeClass("show");
            $(".modal-overlay").removeClass("show");
        },
        error: function(response) {
            /*
            show validation error
            */
            console.log(response)
            $("#js-save-cuisine-button").prop('disabled', false);
            if (typeof response.responseJSON.messages.errors !== 'undefined')
            {
                let errors = response.responseJSON.messages.errors;

                let nameValidation = "";
                if (typeof errors.name !== 'undefined')
                {
                    nameValidation = '<li>' + errors.name + '</li>';
                }

                let errorHtml = '<div class="alert-danger-message" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '</div>';
                $("#error-div").html(errorHtml);
            }
        }
    });
}

/*
sumbit the form and will update a record
*/
function updateCuisine()
{
    $("#js-save-cuisine-button").prop('disabled', true);
    let data = {
        name: $("#name").val(),
    };
    $.ajax({
        url: "/cuisines/edit/" + $("#update_id").val(),
        method: "PUT",
        data: data,
        success: function(response) {
            $("#js-save-cuisine-button").prop('disabled', false);
            let successHtml = '<div class="alert-success-message" role="alert"><b>Cuisine Name Updated Successfully</b></div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            showAllCuisines();
            $("#form-modal").removeClass("show");
            $(".modal-overlay").removeClass("show");
        },
        error: function(response) {
            /*
            show validation error
            */
            console.log(response)
            $("#js-save-cuisine-button").prop('disabled', false);
            if (typeof response.responseJSON.messages.errors !== 'undefined')
            {
                let errors = response.responseJSON.messages.errors;

                let nameValidation = "";
                if (typeof errors.name !== 'undefined')
                {
                    nameValidation = '<li>' + errors.name + '</li>';
                }

                let errorHtml = '<div class="alert-danger-message" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '<ul>' + nameValidation + '</ul>' +
                    '</div>';
                $("#error-div").html(errorHtml);
            }
        }
    });
}

/*
show modal for creating a record and
empty the values of form and remove existing alerts
*/
function createCuisines() {
    $("#alert-div").html("");
    $("#error-div").html("");
    $("#update_id").val("");
    $("#name").val("");
    $("#form-modal").addClass("show");
    $(".modal-overlay").addClass("show");
}

/*
delete record function
*/
function destroyCuisine(id)
{
    $.ajax({
        url: "/cuisines/delete/" + id,
        method: "DELETE",
        success: function(response) {
            let successHtml = '<div class="alert-success-message" role="alert"><b>Cuisine Deleted Successfully</b></div>';
            $("#alert-div").html(successHtml);
            showAllCuisines();
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
}