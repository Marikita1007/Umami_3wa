
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

    // Store the current URL
    let currentUrl = window.location.href;

    // Assume the URL looks like something "https://localhost:8000/recipes/recipe/recipe-id"
    // Use a regular expression to extract the recipe id
    let match = currentUrl.match(/\/details-produit\/([^\/]+)$/);

    // Check if the match is successful and retrieve the recipe id
    let id = match ? match[1] : null;

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

                // Create a div + its content and insert it into the DOM using the parent container
                const new_comment = document.createElement('div');
                new_comment.innerHTML =
                `
                <h3 class="text-warning">Name: ${data.name}</h3>
                <p>Message: ${data.content}</p>`;

                // Add to the parent
                comment_parent.appendChild(new_comment);

                // Clear the form
                comment_form.reset();

                // Optionally refresh the page
                window.location.reload();
            })
            // The promise is not fulfilled, an error is returned
            .catch(error => console.log(error));
    }
});

