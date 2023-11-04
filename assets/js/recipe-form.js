
// todo marika MOVE jquery HERE
// document.addEventListener("DOMContentLoaded", function() {
//
//     // Implemented the ability to add materials using JavaScript
//     document.querySelector('.add-item-link-js').addEventListener('click', function () {
//
//         const container = document.getElementById('ingredient-container');
//         const index = container.getAttribute('data-index');
//
//         // Create a new ingredient field with empty inputs
//         const newIngredient = document.createElement('li');
//         newIngredient.innerHTML = `<input type="text" name="form[ingredients][${index}][name]" placeholder="Ingredient name">
//                                   <input type="text" name="form[ingredients][${index}][amount]" placeholder="Amount">`;
//
//         container.querySelector('ul.ingredients').appendChild(newIngredient);
//
//         container.setAttribute('data-index', parseInt(index) + 1);
//
//         container.dataset.index++;
//
//         // add a delete link to the new form
//         addIngredientFormDeleteLink(newIngredient);
//     });
//
//     document
//         .querySelectorAll('ul.ingredients li')
//         .forEach((ingredient) => {
//             addIngredientFormDeleteLink(ingredient)
//         });
//
//     const addIngredientFormDeleteLink = (item) => {
//
//         const removeFormButton = document.createElement('button');
//         removeFormButton.innerText = 'Delete this Ingredient';
//
//         removeFormButton.classList.add('custom-button-small');
//
//         item.append(removeFormButton);
//
//         removeFormButton.addEventListener('click', (e) => {
//             e.preventDefault();
//
//             // remove the li for the Ingredient form
//             item.remove();
//         });
//     }
// });


// document.addEventListener("DOMContentLoaded", function() {
//
//     // Implemented the ability to add materials using JavaScript
//     document.querySelector('.add-item-link-js').addEventListener('click', function () {
//
//         const container = document.getElementById('ingredient-container');
//         const index = container.getAttribute('data-index');
//         const newIngredient = container.querySelector('ul.ingredients').cloneNode(true);
//
//         // TODO MARIKA Add JS to only show the existing ingredients once
//         // // Clear the input values for the new ingredient
//         // newIngredient.querySelectorAll('input').forEach((input) => {
//         //     input.value = '';
//         // });
//
//         newIngredient.innerHTML = newIngredient.innerHTML.replace(/__name__/g, index);
//
//         container.appendChild(newIngredient);
//
//         container.setAttribute('data-index', parseInt(index) + 1);
//
//         container.dataset.index++;
//
//         // add a delete link to the new form
//         addIngredientFormDeleteLink(newIngredient);
//     });
//
//
//     document
//         .querySelectorAll('ul.ingredients li')
//         .forEach((ingredient) => {
//             addIngredientFormDeleteLink(ingredient)
//         });
//
//     const addIngredientFormDeleteLink = (item) => {
//         const removeFormButton = document.createElement('button');
//         removeFormButton.innerText = 'Delete this Ingredient';
//
//         removeFormButton.classList.add('custom-button-small');
//
//         item.append(removeFormButton);
//
//         removeFormButton.addEventListener('click', (e) => {
//             e.preventDefault();
//
//             // remove the li for the Ingredient form
//             item.remove();
//         });
//     }
// });
//
//
//
// // TODO MARIKA This code down below is not used actually.
// // See if we needs it
//
// //  wait for page to load
// // document.addEventListener("DOMContentLoaded", function() {
// //
// //     const addFormToCollection = (e) => {
// //
// //         const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
// //
// //         const item = document.createElement('li')
// //
// //         item.innerHTML = collectionHolder
// //             .dataset
// //             .prototype
// //             .replace(
// //                 /__name__/g,
// //                 collectionHolder.dataset.index
// //             );
// //
// //         collectionHolder.appendChild(item);
// //
// //         collectionHolder.dataset.index++;
// //
// //         // add a delete link to the new form
// //         addIngredientFormDeleteLink(item);
// //     };
// //
// //     // Add an alert for testing
// //     document
// //         .querySelectorAll('.add-item-link-js')
// //         .forEach(btn => {
// //             btn.addEventListener("click", addFormToCollection);
// //         });
// //
// //     document
// //         .querySelectorAll('ul.ingredients li')
// //         .forEach((ingredient) => {
// //             addIngredientFormDeleteLink(ingredient)
// //         });
// //
// //     const addIngredientFormDeleteLink = (item) => {
// //         const removeFormButton = document.createElement('button');
// //         removeFormButton.innerText = 'Delete this Ingredient';
// //
// //         removeFormButton.classList.add('custom-button-small');
// //
// //         item.append(removeFormButton);
// //
// //         removeFormButton.addEventListener('click', (e) => {
// //             e.preventDefault();
// //
// //             // remove the li for the Ingredient form
// //             item.remove();
// //         });
// //     }
// // });
