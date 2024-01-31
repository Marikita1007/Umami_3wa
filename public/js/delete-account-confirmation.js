document.addEventListener("DOMContentLoaded", function () {

    // Function to show the confirmation modal for deleting an account
    window.showConfirmationModal = function () {

        // Get the confirmation message element by ID
        const confirmationMessageElement = document.getElementById('confirmation-message');

        // Check if the element exists before attempting to set its text content
        if (confirmationMessageElement) {
            // Set the confirmation message
            confirmationMessageElement.textContent = `Are you sure you want to delete your account?`;

            // Display the confirmation modal
            document.getElementById('confirmation-account-modal').style.display = 'block';
        } else {
            console.error('Element with ID "confirmation-message" not found.');
        }
    }

    // Function to hide the confirmation modal
    window.hideConfirmationModal = function () {
        document.getElementById('confirmation-account-modal').style.display = 'none';
    }

    // Function to handle the delete operation (optional)
    window.confirmDelete = function () {
        hideConfirmationModal(); // Hide the confirmation modal
    }

    // Function to cancel the delete operation
    window.cancelDelete = function () {
        // Hide the confirmation modal without proceeding with the delete operation
        hideConfirmationModal();
    }

    // Function to submit the delete account form
    window.submitDeleteAccountForm = function () {
        // Find the form with the class 'delete-account-form'
        const deleteAccountForm = document.getElementById('deleteAccountForm');

        // Check if the form exists before attempting to submit it
        if (deleteAccountForm) {
            // Submit the form
            deleteAccountForm.submit();
        } else {
            console.error('Delete account form not found.');
        }
    }
});