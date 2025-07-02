document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signupForm");
    const alertBox = document.getElementById("jsAlert");
    const alertMessage = document.getElementById("alertMessage");

    form.addEventListener("submit", function (e) {
        const fullName = document.getElementById("full_name").value.trim();
        const email = document.getElementById("email").value.trim();
        const mobile = document.getElementById("mobile_number").value.trim();
        const password = document.getElementById("password").value.trim();

        // Reset alert
        alertBox.classList.add("d-none");
        alertMessage.innerHTML = "";

        let errors = [];

        // Individual required field checks
        if (!fullName) errors.push("Full name is required.");
        if (!email) errors.push("Email is required.");
        if (!mobile) errors.push("Mobile number is required.");
        if (!password) errors.push("Password is required.");

        // Format validations (only run if fields are filled)
        const mobileRegex = /^[6-9]\d{9}$/;
        if (mobile && !mobileRegex.test(mobile)) {
            errors.push("Please enter a valid 10-digit Indian mobile number.");
        }

        const emailRegex = /^[^@]+@[^@]+\.[^@]+$/;
        if (email && !emailRegex.test(email)) {
            errors.push("Please enter a valid email address.");
        }

        if (password && password.length < 6) {
            errors.push("Password must be at least 6 characters long.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alertBox.classList.remove("d-none");
            alertMessage.innerHTML = errors.join("<br>");
        }
    });

    // ✅ Toggle Password Visibility
    const toggleBtn = document.getElementById("togglePassword");
    const toggleIcon = document.getElementById("toggleIcon");
    const passwordInput = document.getElementById("password");

    if (toggleBtn && toggleIcon && passwordInput) {
        toggleBtn.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye-slash");
                toggleIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye");
                toggleIcon.classList.add("bi-eye-slash");
            }
        });
    }
});
