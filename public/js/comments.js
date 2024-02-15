document.addEventListener('DOMContentLoaded', function () {
    const commentForm = document.querySelector('.comments');
    const commentWrapper = document.getElementById('comment-parent');

    commentForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(commentForm);

        // Assuming this.action is the URL for submitting comments
        fetch(this.action, {
            method: this.method,
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                let flashMessageContainer = document.getElementById('comments-flash-message-container');
                let flashMessageType = data.success ? 'success' : 'error';

                // Display the Flash message returned from the server
                flashMessageContainer.textContent = data.message || '';
                flashMessageContainer.classList.add(`alert-${flashMessageType}-message`, 'alert-flash');
                flashMessageContainer.setAttribute('role', 'alert');

                // Clear the form
                commentForm.reset();

                // Set a timeout to remove the Flash message after 5 seconds
                setTimeout(function () {
                    flashMessageContainer.classList.remove(`alert-${flashMessageType}-message`, 'alert-flash');
                    flashMessageContainer.removeAttribute('role');
                    flashMessageContainer.textContent = '';
                }, 5000);


                // If it's an error, handle validation errors if they exist
                if (data.errors) {
                    console.log(data.errors);

                    let errorContainer = document.getElementById('comments-flash-message-container');
                    errorContainer.innerHTML = '';

                    // Create a Set to store unique error messages
                    const uniqueErrors = new Set(data.errors);

                    // Display form errors
                    uniqueErrors.forEach(errorMessage => {
                        const errorItemElement = document.createElement('div');
                        errorItemElement.classList.add('alert-danger-message');
                        errorItemElement.textContent = errorMessage;
                        errorContainer.appendChild(errorItemElement);
                    });

                    // errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    // If it's a success, add the new comment to the comment section
                    if (data.success) {

                        // Display the Flash message returned from the server
                        flashMessageContainer.textContent = "Your comment added successfully !";

                        const newCommentContainer = document.createElement('div');
                        newCommentContainer.classList.add('comment-container');

                        const nameElement = document.createElement('h4');
                        nameElement.textContent = `Name: ${data.commentUsername}`;

                        const postedInfoElement = document.createElement('p');
                        postedInfoElement.classList.add('posted-info');
                        postedInfoElement.textContent = `Posted on ${data.datetime}`;

                        const contentElement = document.createElement('p');
                        contentElement.textContent = data.content;

                        newCommentContainer.appendChild(nameElement);
                        newCommentContainer.appendChild(postedInfoElement);
                        newCommentContainer.appendChild(contentElement);

                        commentWrapper.insertBefore(newCommentContainer, commentWrapper.firstChild);

                        // Clear the form again (if needed)
                        commentForm.reset();
                    }
                }
            })
            .catch(error => console.log(error));
    });
});
