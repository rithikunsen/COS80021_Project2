function register() {
    const registerForm = document.getElementById("register-form");
    const registerButton = document.getElementById("register-button");
    const registerResult = document.getElementById("register-result");

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();
        registerButton.disabled = true; // Disable the button during the request

        // Extract form data
        const firstName = document.getElementById("first_name").value;
        const surname = document.getElementById("surname").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        // Create an XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Define the callback function to handle the response
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Request was successful
                const response = xhr.responseText;

                // Display the result in the registration-result div
                registerResult.innerHTML = response;

                // Re-enable the register button
                registerButton.disabled = false;

                if (parseInt(response) === 200) {
                    window.location.href = "home.html";
                } else {
                    console.log('error')
                    registerResult.innerHTML = response;
                    // registerButton.disabled = false;
                }

            } else {
                // Request failed
                registerResult.innerHTML = "An error occurred.";
                registerButton.disabled = false;
            }
        };

        // Prepare the request
        xhr.open("POST", "register.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Send the request with form data
        xhr.send(
            "first_name=" + firstName +
            "&surname=" + surname +
            "&email=" + email +
            "&password=" + password +
            "&confirm_password=" + confirmPassword
        );
    });

    // Bootstrap form validation
    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();
        event.stopPropagation();
        registerForm.classList.add("was-validated");
    });

    // Reset the form when the Reset button is clicked
    const resetButton = document.querySelector('[name="reset"]');
    resetButton.addEventListener("click", function () {
        registerResult.innerHTML = '';
        registerForm.classList.remove("was-validated");
    });
}

// Call the register function to initialize the registration behavior
document.addEventListener("DOMContentLoaded", register);
