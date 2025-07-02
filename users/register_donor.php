<?php
session_start();
include_once("../db_connect.php");

// ✅ Correct session keys from login.php
$user = $_SESSION['user'] ?? null;
$role = $_SESSION['role'] ?? null;

// 🔒 Redirect if user not logged in
if (!$user) {
    header("Location: ../login.php");
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $city = trim($_POST['city']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $last_donated = !empty($_POST['last_donated']) ? $_POST['last_donated'] : null;

    $errors = [];

    // Server-side validation
    if (empty($name) || empty($city) || empty($phone)) {
        $errors[] = "Please fill in all required fields.";
    }
    if (!filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 18, "max_range" => 65]])) {
        $errors[] = "Age must be between 18 and 65.";
    }
    if (empty($gender)) {
        $errors[] = "Please select your gender.";
    }
    if (empty($blood_group)) {
        $errors[] = "Please select your blood group.";
    }
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Insert or show errors
    if (!empty($errors)) {
        $message = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">';
        foreach ($errors as $err) {
            $message .= "<li>$err</li>";
        }
        $message .= '
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO donors (name, age, gender, blood_group, city, phone, email, last_donated) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssss", $name, $age, $gender, $blood_group, $city, $phone, $email, $last_donated);

        if ($stmt->execute()) {
            $message = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Donor registered successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        } else {
            $message = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i> Failed: ' . $stmt->error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Donor | BloodDonorSeeker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../js/validate_donor.js" defer></script>
</head>

<body style="background-color: #FFA07A;">

    <!-- ✅ NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fs-4 fw-semibold" href="index.php">
                <img src="../images/logo.png" alt="Logo" width="50" height="50" class="me-2">
                BloodDonorSeeker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav fs-5 align-items-center">

                    <!-- 🔍 Always Visible -->
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
                    <!-- ✅ Logged-in Navigation -->
                    <li class="nav-item">
                        <a class="nav-link text-light" href="request_blood.php">
                            <i class="bi bi-clipboard2-heart-fill text-danger me-1"></i> Request Blood
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="logout.php">
                            <i class="bi bi-box-arrow-right text-warning me-1"></i> Logout
                        </a>
                    </li>

                    <?php else: ?>
                    <!-- 🔐 Guest Navigation -->
                    <li class="nav-item">
                        <a class="nav-link text-light" href="login.php">
                            <i class="bi bi-box-arrow-in-right text-primary me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="signup.php">
                            <i class="bi bi-person-plus-fill text-success me-1"></i> Signup
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    <h1 class="text-center mt-3 fw-bold text-dark">Donor Registration Form</h1>

    <!-- Donor Registration Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 col-md-12 border border-dark border-5 mb-4 mt-3">
                <div class="card border-0 shadow-lg">
                    <div class="card-header text-white py-4 bg-danger">
                        <h3 class="mb-0 fw-bold"><i class="bi bi-droplet-fill me-2"></i> Register as a Donor</h3>
                    </div>
                    <div class="card-body px-5 py-4" style="background-color:#e6f4f1;">
                        <div id="jsErrorBox" class="alert alert-danger d-none"></div>
                        <?= $message ?>
                        <form method="POST" action="" id="donorForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-person-fill me-1 text-primary"></i> Full Name</label>
                                <input type="text" name="name" class="form-control shadow-sm">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-hourglass-top me-1 text-primary"></i> Age</label>
                                <input type="number" name="age" class="form-control shadow-sm" min="18" max="65">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-gender-ambiguous me-1 text-primary"></i> Gender</label>
                                <select name="gender" class="form-select shadow-sm">
                                    <option value="">--Select--</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-droplet-fill me-1 text-primary"></i> Blood Group</label>
                                <select name="blood_group" class="form-select shadow-sm">
                                    <option value="">--Select--</option>
                                    <option>A+</option>
                                    <option>A-</option>
                                    <option>B+</option>
                                    <option>B-</option>
                                    <option>O+</option>
                                    <option>O-</option>
                                    <option>AB+</option>
                                    <option>AB-</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-geo-alt-fill me-1 text-primary"></i> City</label>
                                <input type="text" name="city" class="form-control shadow-sm">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-telephone-fill me-1 text-primary"></i> Phone</label>
                                <input type="tel" name="phone" class="form-control shadow-sm" pattern="[0-9]{10}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-envelope-fill me-1 text-primary"></i> Email (Optional)</label>
                                <input type="email" name="email" class="form-control shadow-sm"
                                    placeholder="example@gmail.com">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold"><i
                                        class="bi bi-calendar-event-fill me-1 text-primary"></i> Last Donated
                                    Date</label>
                                <input type="date" name="last_donated" class="form-control shadow-sm">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle-fill me-1"></i> Register
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <small><i class="bi bi-info-circle"></i> Only users aged 18 to 65 can register as
                            donors.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>