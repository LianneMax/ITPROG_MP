document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");
    const content = document.querySelector(".content");

    // Toggle sidebar on hover and shift content
    hamburger.addEventListener("mouseenter", function () {
        sidebar.classList.add("active");
        content.classList.add("shifted");  // Shift content when sidebar is active
    });

    sidebar.addEventListener("mouseleave", function () {
        sidebar.classList.remove("active");
        content.classList.remove("shifted");
    });
});

