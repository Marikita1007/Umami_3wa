
// Scroll and arrow appears
window.addEventListener('scroll', function(){

    const backToTopButton = document.getElementById('back-to-top');

    if (window.scrollY > 500) {
        backToTopButton.classList.add('visible');
    } else {
        backToTopButton.classList.remove('visible');
    }

    // Scroll to top when the arrow is clicked
    document.getElementById('back-to-top').addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {

    // Responsive navbar
    document.getElementById('toggleNav').addEventListener('click', function() {
        let myTopnav = document.getElementById("myTopnav");

        // Check if the class list includes "responsive"
        if (myTopnav.classList.contains("responsive")) {
            myTopnav.classList.remove("responsive");
        } else {
            myTopnav.classList.add("responsive");

            // Check and toggle dark mode if needed
            const storedDarkMode = localStorage.getItem('darkMode');
            const isDarkMode = storedDarkMode === 'true' || false;
            // Add your dark mode logic here if needed
        }
    });

    // Handle Dark Mode
    // Check user preference on page load (e.g., from localStorage)
    // Default to light mode if not set
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    // Update the class of the main content
    updateDarkModeClass(isDarkMode);

    // Dark mode toggle click event
    document.getElementById('darkModeToggle').addEventListener('click', function () {
        // Toggle dark mode status
        const isDarkMode = !localStorage.getItem('darkMode') || localStorage.getItem('darkMode') === 'false';
        localStorage.setItem('darkMode', isDarkMode);

        // Update the class of the main content
        updateDarkModeClass(isDarkMode);
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

