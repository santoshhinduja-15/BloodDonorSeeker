<?php
session_start();
include '../db_connect.php'; // Adjust path if needed

$user = $_SESSION['user'] ?? null;

$success = $error = "";
$formData = [
    'fullname' => '',
    'blood_group' => '',
    'city' => '',
    'phone' => '',
    'email' => '',
    'urgency' => '',
    'reason' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $key => &$value) {
        if (isset($_POST[$key])) {
            $value = trim($_POST[$key]);
        }
    }

    $formData['urgency'] = $_POST['urgency'] ?? '';

    if ($formData['fullname'] && $formData['blood_group'] && $formData['city'] &&
        $formData['phone'] && $formData['email'] && $formData['urgency'] && $formData['reason']) {

        $stmt = $conn->prepare("INSERT INTO blood_requests 
            (fullname, blood_group, city, phone, email, urgency, reason, request_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->bind_param(
            "sssssss",
            $formData['fullname'],
            $formData['blood_group'],
            $formData['city'],
            $formData['phone'],
            $formData['email'],
            $formData['urgency'],
            $formData['reason']
        );

        if ($stmt->execute()) {
            $success = "Your blood request has been submitted successfully.";
            $formData = array_fill_keys(array_keys($formData), '');
        } else {
            $error = "Failed to submit your request. Please try again.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = "Please fill out all the required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Blood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #FFA07A;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fs-4 fw-semibold" href="../index.php">
            <img src="../images/logo.png" alt="Logo" width="50" height="50" class="me-2">
            BloodDonorSeeker
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav fs-5 align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-light" href="../index.php">
                        <i class="bi bi-house-door-fill text-primary me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="search.php">
                        <i class="bi bi-search-heart-fill text-danger me-1"></i> Search Donors
                    </a>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="register_donor.php">
                            <i class="bi bi-droplet-fill text-danger me-1"></i> Register Donor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../logout.php">
                            <i class="bi bi-box-arrow-right text-warning me-1"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../login.php">
                            <i class="bi bi-box-arrow-in-right text-primary me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../signup.php">
                            <i class="bi bi-person-plus-fill text-success me-1"></i> Signup
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Alerts -->
<div class="container py-4">
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Request Form -->
<div class="container pb-5">
    <div class="card border-dark border-5 col-md-6 mx-auto">
        <div class="card-body">
            <form method="POST" id="requestForm" novalidate>
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person-fill"></i> Full Name *</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($formData['fullname']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-droplet-fill text-danger"></i> Blood Group *</label>
                    <select name="blood_group" class="form-select" required>
                        <option value="">Select</option>
                        <?php
                        $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                        foreach ($groups as $group) {
                            $selected = ($formData['blood_group'] == $group) ? 'selected' : '';
                            echo "<option $selected>$group</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-geo-alt-fill"></i> City *</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($formData['city']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-telephone-fill"></i> Phone *</label>
                    <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" value="<?= htmlspecialchars($formData['phone']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-envelope-fill"></i> Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-lightning-charge-fill text-warning"></i> Urgency *</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="urgency" value="Urgent" class="form-check-input" <?= $formData['urgency'] === 'Urgent' ? 'checked' : '' ?>>
                        <label class="form-check-label"><i class="bi bi-exclamation-circle-fill text-danger"></i> Urgent</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="urgency" value="Normal" class="form-check-input" <?= $formData['urgency'] === 'Normal' ? 'checked' : '' ?>>
                        <label class="form-check-label"><i class="bi bi-clock-fill text-secondary"></i> Normal</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-journal-text"></i> Reason for Request *</label>
                    <textarea name="reason" class="form-control" rows="3" required><?= htmlspecialchars($formData['reason']) ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-send-fill"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
