// // The parent <div> of the comment content
// const commentWrapper = document.getElementById('comment-parent')
//
// // The comment form
// const commentForm = document.querySelector('.comments')
//
//
// document.addEventListener('DOMContentLoaded', function () {
// // On form submission
//     commentForm.addEventListener('submit', function (e) {
//
//         // Prevent the default behavior (page reload)
//         e.preventDefault();
//
//         // FormData interface = key-value object constructor representing the fields of a form
//         const formData = new FormData(commentForm);
//         // console.log(formData);
//
//         // Store the current URL
//         let currentUrl = window.location.href;
//
//         // Assume the URL looks like something "https://localhost:8000/recipes/recipe/recipe-id"
//         // Use a regular expression to extract the recipe id
//         let match = currentUrl.match(/\/recipe\/([^\/]+)$/);
//
//         // Assume the URL looks like something "https://localhost:8000/recipes/show-the-meal-db-recipe-details/recipe-id"
//         // Use a regular expression to extract the recipe id
//         let matchTheMealDb = currentUrl.match(/\/show-the-meal-db-recipe-details\/([^\/]+)$/);
//
//         // Check if the match is successful and retrieve the recipe id
//         let id = match ? match[1] : null;
//
//         // Check if the match is successful and retrieve the recipe idMeal of The Meal DB API
//         let idMeal = matchTheMealDb ? matchTheMealDb[1] : null;
//         // console.log(id);
//         if (id || idMeal) {
//             // Set up the request using a promise
//             fetch(this.action, {
//                 // HTTP Post method
//                 method: this.method,
//                 // Request body = formData object = form values
//                 body: formData,
//             })
//
//                 // The promise returns JSON-formatted values
//                 .then(response => response.json())
//                 // Data is deserialized and injected into the DOM
//                 .then(data => {
//
//                     // Display the flash message
//                     const flashMessageContainer = document.getElementById('flash-message-container');
//                     flashMessageContainer.textContent = 'Your comment is registered successfully!';
//                     flashMessageContainer.classList.add('alert-success-message', 'alert-flash');
//                     flashMessageContainer.setAttribute('role', 'alert'); //Enhance accessibility by adding role of the flashbag.
//
//                     // Clear the form
//                     commentForm.reset();
//
//                     // Set a timeout to remove the flash message after 5 seconds
//                     setTimeout(function () {
//                         // Remove the added classes and flash message
//                         flashMessageContainer.classList.remove('alert-success-message', 'alert-flash');
//                         flashMessageContainer.removeAttribute('alert');
//                         flashMessageContainer.textContent = '';
//                     }, 3000);
//
//                     // Create a div + its content and insert it into the DOM using the parent container
//                     const newCommentContainer = document.createElement('div');
//                     newCommentContainer.classList.add('comment-container');
//
//                     const nameElement = document.createElement('h4');
//                     nameElement.textContent = `Name: ${data.commentUsername}`;
//
//                     const postedInfoElement = document.createElement('p');
//                     postedInfoElement.classList.add('posted-info');
//                     postedInfoElement.textContent = `Posted on ${data.datetime}`;
//
//                     const contentElement = document.createElement('p');
//                     contentElement.textContent = data.content;
//
//                     // Append child elements to the new comment container
//                     newCommentContainer.appendChild(nameElement);
//                     newCommentContainer.appendChild(postedInfoElement);
//                     newCommentContainer.appendChild(contentElement);
//
//                     // Insert the new comment container at the beginning of the comment container
//                     commentWrapper.insertBefore(newCommentContainer, commentWrapper.firstChild);
//
//                     // Clear the form
//                     commentForm.reset();
//                 })
//                 // The promise is not fulfilled, an error is returned
//                 .catch(error => console.log(error));
//         }
//     });
//
// });

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
