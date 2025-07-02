<?php
include 'db_connect.php';

$message = "";
$alertClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if (empty($full_name) || empty($email) || empty($mobile_number) || empty($_POST['password']) || empty($role)) {
        $message = "Please fill in all fields.";
        $alertClass = "alert-warning";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, mobile_number, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $mobile_number, $password, $role);

        if ($stmt->execute()) {
            $message = "Signup successful!";
            $alertClass = "alert-success";
        } else {
            $message = "Error: " . $stmt->error;
            $alertClass = "alert-danger";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Signup - Blood Donor Seeker</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="js/signup-validation.js" defer></script>
</head>

<body style="background-color: #F0FFFF;">

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fs-4 fw-semibold" href="#">
                <img src="images/logo.png" alt="Logo" width="50" height="50" class="me-2">
                BloodDonorSeeker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav fs-5">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="index.php">
                            <i class="bi bi-house-door-fill text-primary me-1"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="login.php">
                            <i class="bi bi-box-arrow-in-right text-primary me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Signup Form -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-lg border border-dark border-5">
                    <div class="card-header bg-danger text-white text-center">
                        <h3><i class="bi bi-person-plus-fill me-2"></i>Signup</h3>
                    </div>
                    <div class="card-body">

                        <!-- ✅ PHP Alert -->
                        <?php if (!empty($message)): ?>
                        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i><?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <!-- ✅ Form Start -->
                        <form id="signupForm" method="post" action="">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control"
                                    placeholder="Enter your full name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="you@example.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" id="mobile_number" class="form-control"
                                    placeholder="9876543210">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Choose a password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <!-- ✅ JS Alert -->
                            <div id="jsAlert" class="alert alert-danger alert-dismissible fade show d-none"
                                role="alert">
                                <span id="alertMessage"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-person-plus me-1"></i> Register
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
