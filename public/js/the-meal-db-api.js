console.log('Coucou the-meal-db-api.js');

// Check if recipe details are stored in local storage
var storedRecipeDetails = localStorage.getItem('recipeDetails_{{ theMealDbRecipeDetails.idMeal }}');

// If not, make an API request and store the details in local storage
if (!storedRecipeDetails) {
    fetch('/show-the-meal-db-recipe-details/{{ theMealDbRecipeDetails.idMeal }}')
        .then(response => response.json())
        .then(data => {
            // Store the recipe details in local storage
            localStorage.setItem('recipeDetails_{{ theMealDbRecipeDetails.idMeal }}', JSON.stringify(data));
        });
}