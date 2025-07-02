<?php
session_start();
include 'db_connect.php';

$message = "";
$alertClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
        $alertClass = "alert-warning";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin/approve_donors.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $message = "Invalid email or password.";
                $alertClass = "alert-danger";
            }
        } else {
            $message = "Invalid email or password.";
            $alertClass = "alert-danger";
        }

        $stmt->close();
    }

    $conn->close();
}

$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Blood Donor Seeker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="js/login-validation.js" defer></script>
</head>
<body style="background-color: #F0FFFF;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fs-4 fw-semibold" href="index.php">
            <img src="images/logo.png" alt="Logo" width="50" height="50" class="me-2">
            BloodDonorSeeker
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav fs-5 align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php">
                        <i class="bi bi-house-door-fill text-primary me-1"></i> Home
                    </a>
                </li>

                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="logout.php">
                            <i class="bi bi-box-arrow-right text-warning me-1"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
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

<!-- Login Section -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border border-dark border-5 mb-3">
                <div class="card-header bg-danger text-white text-center">
                    <h3><i class="bi bi-box-arrow-in-right me-2"></i>Login</h3>
                </div>
                <div class="card-body">

                    <!-- PHP alert -->
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- JS alert -->
                    <div id="jsAlert" class="alert alert-danger d-none alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span id="alertMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form method="post" id="loginForm" action="">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope-fill me-1 text-primary"></i>Email
                            </label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill me-1 text-primary"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </div>
                    </form>

                    <p class="mt-4 text-center">
                        <i class="bi bi-person-plus-fill me-1"></i>
                        Don't have an account? <a href="signup.php" class="text-decoration-none">Sign up</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
