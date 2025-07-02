document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const alertBox = document.getElementById("jsAlert");
    const alertMessage = document.getElementById("alertMessage");

    form.addEventListener("submit", function (e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        alertBox.classList.add("d-none");
        alertMessage.innerHTML = "";

        let errors = [];

        if (!email) errors.push("Email is required.");
        if (!password) errors.push("Password is required.");

        const emailRegex = /^[^@]+@[^@]+\.[^@]+$/;
        if (email && !emailRegex.test(email)) {
            errors.push("Please enter a valid email address.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alertBox.classList.remove("d-none");
            alertMessage.innerHTML = errors.join("<br>");
        }
    });

    // Toggle password visibility
    const toggleBtn = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (toggleBtn && passwordField && toggleIcon) {
        toggleBtn.addEventListener("click", () => {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            toggleIcon.classList.toggle("bi-eye");
            toggleIcon.classList.toggle("bi-eye-slash");
        });
    }
});
