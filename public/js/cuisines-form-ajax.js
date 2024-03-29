// jQuery for Cuisine CRUD
// This function is called the moment Cuisine Name Page shows
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
function showAllCuisines() {
    $.ajax({
        url: "/cuisines/list",
        method: "GET",
        success: function(response) {

            $("#js-cuisines-table-body").html("");
            let cuisines = response;

            // Sort cuisines in descending order based on ID
            cuisines.sort(function(a, b) {
                return b.id - a.id;
            });

            for (let i = 0; i < cuisines.length; i++) {
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
            let successHtml = '<div class="alert-success-message alert-flash" role="alert">New Cuisine Name Created Successfully</div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            showAllCuisines();
            $("#form-modal").removeClass("show");
            $(".modal-overlay").removeClass("show");

            // Remove success message after 3 seconds
            setTimeout(function() {
                $("#alert-div").empty();
            }, 3000);
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

                let errorHtml = '<div class="alert-danger-message alert-flash" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '<ul>';

                // Loop through the errors and add them to the errorHtml
                Object.entries(errors).forEach(([key, value]) => {
                    errorHtml += '<li>' + value + '</li>';
                });

                errorHtml += '</ul></div>';
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
            let successHtml = '<div class="alert-success-message alert-flash" role="alert">Cuisine Name Updated Successfully</div>';
            $("#alert-div").html(successHtml);
            $("#name").val("");
            showAllCuisines();
            $("#form-modal").removeClass("show");
            $(".modal-overlay").removeClass("show");

            // Remove success message after 3 seconds
            setTimeout(function() {
                $("#alert-div").empty();
            }, 3000);
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

                let errorHtml = '<div class="alert-danger-message alert-flash" role="alert">' +
                    '<b>Validation Error!</b>' +
                    '<ul>';

                // Loop through the errors and add them to the errorHtml
                Object.entries(errors).forEach(([key, value]) => {
                    errorHtml += '<li>' + value + '</li>';
                });

                errorHtml += '</ul></div>';
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
function showConfirmationModal() {
    $("#confirmation-cuisine-modal").show();
}

function hideConfirmationModal() {
    $("#confirmation-cuisine-modal").hide();
}

function confirmDelete() {
    hideConfirmationModal();
    // Proceed with the delete operation
    if (typeof confirmDeleteHandler === 'function') {
        confirmDeleteHandler();
    }
}

function cancelDelete() {
    hideConfirmationModal();
}

// Variable to hold the delete operation handler
let confirmDeleteHandler = null;

function destroyCuisine(id) {
    // Set up the confirmation modal before making the AJAX request
    showConfirmationModal();

    // Handle the delete operation in the confirmDelete function
    confirmDeleteHandler = function () {
        $.ajax({
            url: "/cuisines/delete/" + id,
            method: "DELETE",
            success: function(response) {
                let successHtml = '<div class="alert-success-message alert-flash" role="alert">Cuisine Deleted Successfully</div>';
                $("#alert-div").html(successHtml);
                showAllCuisines();

                // Remove success message after 5 seconds
                setTimeout(function() {
                    $("#alert-div").empty();
                }, 5000);
            },
            error: function(response) {
                console.log(response.responseJSON);
            }
        });
    }
}

