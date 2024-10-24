document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    // Function to open the sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        content.classList.add('shifted');
    }

    // Function to close the sidebar
    function closeSidebar() {
        sidebar.classList.remove('active');
        content.classList.remove('shifted');
    }

    // Ensure these elements exist on the page before adding event listeners
    if (hamburger && sidebar && content) {
        // Open sidebar when hovering over hamburger or sidebar
        hamburger.addEventListener('mouseenter', openSidebar);
        sidebar.addEventListener('mouseenter', openSidebar);

        // Close sidebar only when leaving the sidebar
        sidebar.addEventListener('mouseleave', closeSidebar);
    }
});
