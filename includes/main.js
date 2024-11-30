document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const content = document.querySelector(".content");
    const hamburger = document.getElementById("hamburger");

    // Function to add active class to both sidebar and content
    function activateSidebar() {
        sidebar.classList.add("open");
        content.classList.add("shifted");
    }

    // Function to remove active class from both sidebar and content
    function deactivateSidebar() {
        sidebar.classList.remove("open");
        content.classList.remove("shifted");
    }

    // Expand sidebar and shift content on mouse enter for sidebar and hamburger
    sidebar.addEventListener("mouseenter", activateSidebar);

    // Collapse sidebar and reset content on mouse leave for sidebar and hamburger
    sidebar.addEventListener("mouseleave", deactivateSidebar);

    
});