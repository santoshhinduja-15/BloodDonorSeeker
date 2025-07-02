document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("donorForm");
    const alertBox = document.getElementById("jsErrorBox");

    form.addEventListener("submit", function (event) {
        const name = form.name.value.trim();
        const age = parseInt(form.age.value.trim());
        const gender = form.gender.value;
        const bloodGroup = form.blood_group.value;
        const city = form.city.value.trim();
        const phone = form.phone.value.trim();
        const email = form.email.value.trim();

        let errors = [];

        if (name === "" || city === "" || phone === "") {
            errors.push("Please fill in all required fields.");
        }
        if (isNaN(age) || age < 18 || age > 65) {
            errors.push("Age must be between 18 and 65.");
        }
        if (!gender) {
            errors.push("Please select your gender.");
        }
        if (!bloodGroup) {
            errors.push("Please select your blood group.");
        }
        if (!/^\d{10}$/.test(phone)) {
            errors.push("Phone number must be exactly 10 digits.");
        }
        if (email && !/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Please enter a valid email address.");
        }

        if (errors.length > 0) {
            event.preventDefault();
            alertBox.className = "alert alert-danger";
            alertBox.innerHTML = "<ul class='mb-0'>" + errors.map(err => `<li>${err}</li>`).join("") + "</ul>";
            alertBox.classList.remove("d-none");
            alertBox.scrollIntoView({ behavior: "smooth" });
        } else {
            alertBox.classList.add("d-none");
        }
    });
});
