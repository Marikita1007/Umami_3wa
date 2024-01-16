// Responsive navbar
function responsiveNav() {
    let myTopnav = document.getElementById("myTopnav");
    if (myTopnav.className === "topnav") {
        myTopnav.classList.add("responsive");
    } else {
        myTopnav.className = "topnav";
    }
}


// Scroll and arrow appears
window.addEventListener('scroll', function(){

    if(window.scrollY > 500){
        document.getElementById('back-to-top').style.display = 'block';
    } else {
        document.getElementById('back-to-top').style.display = 'none';
    }
});

// Handle Dark Mode
document.addEventListener('DOMContentLoaded', function () {
    // Check user preference on page load (e.g., from localStorage)
    // Default to light mode if not set
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    // Function to update the background images based on dark mode status
    function updateBackgroundImages(isDarkMode) {
        const homeSection = document.querySelector('.home-section');

        if (isDarkMode) {
            homeSection.style.background = 'none';
            // Apply a darkening filter
            homeSection.style.filter = 'brightness(70%)'; // Adjust the percentage as needed
        } else {
            homeSection.style.background = 'fixed top 95px right 0 url(images/oranges.jpg) no-repeat, fixed top 640px left 0 url(images/spices.jpg) no-repeat';
            // Remove the filter for the light mode
            homeSection.style.filter = 'brightness(100%)';
        }
    }

    // Update the class of the main content
    updateDarkModeClass(isDarkMode);

    // Dark mode toggle click event
    document.getElementById('darkModeToggle').addEventListener('click', function () {
        // Toggle dark mode status
        const isDarkMode = !localStorage.getItem('darkMode') || localStorage.getItem('darkMode') === 'false';
        localStorage.setItem('darkMode', isDarkMode);

        // Update the class of the main content
        updateDarkModeClass(isDarkMode);

        // Update background images based on dark mode status
        updateBackgroundImages(isDarkMode);
    });

    // Function to update the class of the main content
    function updateDarkModeClass(isDarkMode) {
        const mainContent = document.getElementById('mainContent');
        const footerContent = document.getElementById('footerContent');

        // Remove existing classes
        mainContent.classList.remove('darktheme');

        // Add the appropriate class based on dark mode status
        if (isDarkMode) {
            mainContent.classList.add('darktheme');
        }

        // Remove existing classes
        footerContent.classList.remove('darktheme');

        // Add the appropriate class based on dark mode status
        if (isDarkMode) {
            footerContent.classList.add('darktheme');
        }
    }


});

