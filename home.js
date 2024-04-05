document.addEventListener("DOMContentLoaded", function () {
    const content = document.getElementById("content");

    // Function to load content based on the user's login status
    function loadContent() {
        const xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (xhr.status === 200) {
                content.innerHTML = xhr.responseText;
            } else {
                content.innerHTML = "An error occurred while loading content.";
            }
        };

        xhr.open("GET", "home.php", true);
        xhr.send();
    }

    loadContent(); // Load content when the page is loaded
});
