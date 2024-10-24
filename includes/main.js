document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    // Function to expand the sidebar
    function expandSidebar() {
        sidebar.classList.add('expanded');
        content.classList.add('shifted');
    }

    // Function to collapse the sidebar
    function collapseSidebar() {
        sidebar.classList.remove('expanded');
        content.classList.remove('shifted');
    }

    // Open sidebar when hovering over hamburger or sidebar
    hamburger.addEventListener('mouseenter', expandSidebar);
    sidebar.addEventListener('mouseenter', expandSidebar);

    // Close sidebar when mouse leaves sidebar
    sidebar.addEventListener('mouseleave', collapseSidebar);
});