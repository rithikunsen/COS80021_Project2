function login() {
    const loginForm = document.getElementById("login-form");
    const loginButton = document.getElementById("login-button");
    const loginResult = document.getElementById("login-result");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        loginButton.disabled = true; // Disable the button during the request

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // Create an XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Define the callback function to handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Request was successful
                const response = xhr.responseText;
                loginResult.innerHTML = response;

                // Re-enable the login button
                loginButton.disabled = false;
                console.log('response', response);

                if (parseInt(response) === 200) {
                    window.location.href = "home.html";
                } else {
                    loginResult.innerHTML = response;
                    loginButton.disabled = false;
                }
            } else {
                // Request failed
                loginResult.innerHTML = "An error occurred.";
                loginButton.disabled = false;
            }
        };

        // Prepare the request
        xhr.open("POST", "login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Send the request
        xhr.send("email=" + email + "&password=" + password);
    });

    // Bootstrap form validation
    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        event.stopPropagation();

        // Add Bootstrap's was-validated class to trigger styling
        loginForm.classList.add("was-validated");
    });

    // Reset the form when the Reset button is clicked
    const resetButton = document.querySelector('[name="reset"]');
    resetButton.addEventListener("click", function () {
        //remove the login-result class list
        loginResult.innerHTML = '';
        loginForm.classList.remove("was-validated");
    });
}

// Call the login function to initialize the login behavior
document.addEventListener("DOMContentLoaded", login);
