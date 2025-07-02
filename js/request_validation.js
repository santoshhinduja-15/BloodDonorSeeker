document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("requestForm");
    const alertContainer = document.createElement("div");
    form.parentNode.insertBefore(alertContainer, form);

    form.addEventListener("submit", function (e) {
        alertContainer.innerHTML = ""; // Clear old alerts
        let errors = [];

        const fullname = form.fullname.value.trim();
        const blood_group = form.blood_group.value;
        const city = form.city.value.trim();
        const phone = form.phone.value.trim();
        const email = form.email.value.trim();
        const urgency = form.urgency.value;
        const reason = form.reason.value.trim();

        if (!fullname) errors.push("Full Name is required.");
        if (!blood_group) errors.push("Please select a blood group.");
        if (!city) errors.push("City is required.");
        if (!/^\d{10}$/.test(phone)) errors.push("Phone must be 10 digits.");
        if (!email || !/\S+@\S+\.\S+/.test(email)) errors.push("A valid email is required.");
        if (!urgency) errors.push("Please select urgency.");
        if (!reason) errors.push("Please provide a reason for the request.");

        if (errors.length > 0) {
            e.preventDefault(); // Stop form submission

            errors.forEach((msg) => {
                const alert = document.createElement("div");
                alert.className = "alert alert-danger alert-dismissible fade show";
                alert.role = "alert";
                alert.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                alertContainer.appendChild(alert);
            });
        }
    });
});
