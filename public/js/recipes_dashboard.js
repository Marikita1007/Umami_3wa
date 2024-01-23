// Function to show the confirmation modal for deleting a recipe
function showConfirmationModal(recipeName, deleteUrl) {
    // Get the element for displaying the confirmation message
    const confirmationMessageElement = document.getElementById('confirmation-message');

    // Set the confirmation message with the recipe name
    confirmationMessageElement.textContent = `Are you sure you want to delete the recipe "${recipeName}"?`;

    // Get the confirm button inside the modal
    const confirmButton = document.querySelector('#confirmation-modal .confirm-button');

    // Configure the confirm button to redirect to the delete URL
    confirmButton.onclick = function () {
        // Proceed with the delete operation by redirecting to the delete URL
        window.location.href = deleteUrl;
    };

    // Display the confirmation modal
    document.getElementById('confirmation-modal').style.display = 'block';
}

// Function to hide the confirmation modal
function hideConfirmationModal() {
    document.getElementById('confirmation-modal').style.display = 'none';
}

// Function to handle the delete operation (optional)
function confirmDelete() {
    hideConfirmationModal(); // Hide the confirmation modal
}

// Function to cancel the delete operation
function cancelDelete() {
    // Hide the confirmation modal without proceeding with the delete operation
    hideConfirmationModal();
}
