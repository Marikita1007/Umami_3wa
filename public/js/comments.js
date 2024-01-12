// The parent <div> of the comment content
const comment_parent = document.getElementById('comment-parent')

// The comment form
const comment_form = document.querySelector('.comments')

// On form submission
comment_form.addEventListener('submit', function (e) {

    // Prevent the default behavior (page reload)
    e.preventDefault();

    // FormData interface = key-value object constructor representing the fields of a form
    const formData = new FormData(comment_form);
    // console.log(formData);

    // Store the current URL
    // Store the current URL
    let currentUrl = window.location.href;

    // Assume the URL looks like something "https://localhost:8000/recipes/recipe/recipe-id"
    // Use a regular expression to extract the recipe id
    let match = currentUrl.match(/\/recipe\/([^\/]+)$/);

    // Assume the URL looks like something "https://localhost:8000/recipes/show-the-meal-db-recipe-details/recipe-id"
    // Use a regular expression to extract the recipe id
    let matchTheMealDb = currentUrl.match(/\/show-the-meal-db-recipe-details\/([^\/]+)$/);

    // Check if the match is successful and retrieve the recipe id
    let id = match ? match[1] : null;
    // console.log(id);
    if(id) {
        // Set up the request using a promise
        fetch(this.action, {
            // HTTP Post method
            method: this.method,
            // Request body = formData object = form values
            body: formData,
        })

            // The promise returns JSON-formatted values
            .then(response => response.json())
            // Data is deserialized and injected into the DOM
            .then(data => {

                // Display the flash message
                const flashMessageContainer = document.getElementById('flash-message-container');
                flashMessageContainer.innerHTML =
                    `
                        <div class="alert-success-message alert-flash" role="alert">Your comment is registered successfully ! </div>
                    `;

                // Create a div + its content and insert it into the DOM using the parent container
                const newCommentContainer  = document.createElement('div');

                newCommentContainer.classList.add('comment-container');
                newCommentContainer.innerHTML =
                    `
                <h4 class="">Name: ${data.commentUsername}</h4>
                <p class="posted-info">Posted on ${data.datetime}</p>
                <p>${data.content}</p>`;

                // Add to the parent
                comment_parent.appendChild(newCommentContainer);

                // Clear the form
                comment_form.reset();

                // Optionally refresh the page
                // window.location.reload();
            })
            // The promise is not fulfilled, an error is returned
            .catch(error => console.log(error));
    }

    // Check if the match is successful and retrieve the recipe idMeal of The Meal DB API
    let idMeal = matchTheMealDb ? matchTheMealDb[1] : null;

    if(idMeal) {
        // Set up the request using a promise
        fetch(this.action, {
            // HTTP Post method
            method: this.method,
            // Request body = formData object = form values
            body: formData,
        })

            // The promise returns JSON-formatted values
            .then(response => response.json())
            // Data is deserialized and injected into the DOM
            .then(data => {

                // Display the flash message
                const flashMessageContainer = document.getElementById('flash-message-container');
                flashMessageContainer.innerHTML =
                    `
                        <div class="alert-success-message alert-flash" role="alert">Your comment is registered successfully ! </div>
                    `;

                // Create a div + its content and insert it into the DOM using the parent container
                const newCommentContainer  = document.createElement('div');

                newCommentContainer.classList.add('comment-container');
                newCommentContainer.innerHTML =
                    `
                <h4>Name: ${data.commentUsername}</h4>
                <p class="posted-info">Posted on ${data.datetime}</p>
                <p>${data.content}</p>`;

                // Add to the parent
                comment_parent.appendChild(newCommentContainer);

                // Clear the form
                comment_form.reset();

            })
            // The promise is not fulfilled, an error is returned
            .catch(error => console.log(error));
    }

});
