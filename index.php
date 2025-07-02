<?php
session_start();
$user = $_SESSION['user'] ?? null;
$role = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome | BloodDonorSeeker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body style="background-color: #F0FFFF;">

    <!-- ✅ NAVBAR -->
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

                    <!-- 🔍 Always Visible -->
                    <li class="nav-item">
                        <a class="nav-link text-light" href="users/search.php">
                            <i class="bi bi-search-heart-fill text-danger me-1"></i> Search Donors
                        </a>
                    </li>

                    <?php if ($user): ?>
                        <!-- ✅ Logged-in Navigation -->
                        <li class="nav-item">
                            <a class="nav-link text-light" href="users/register_donor.php">
                                <i class="bi bi-droplet-fill text-danger me-1"></i> Register Donor
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light" href="users/request_blood.php">
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

    <!-- ✅ WELCOME CARD -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg text-center">
                    <div class="card-body p-5" style="background-color: #FFB6C1;">
                        <h1 class="display-5 text-danger mb-4">
                            <i class="bi bi-heart-pulse-fill me-2"></i> Welcome to BloodDonorSeeker
                        </h1>

                        <p class="lead mb-4">
                            Aap <strong>donor</strong> ho? <br>
                            <a href="users/register_donor.php" class="text-success text-decoration-none fw-semibold">
                                <i class="bi bi-person-plus-fill me-1"></i> Register karo
                            </a>
                        </p>

                        <p class="lead">
                            Aapko <strong>blood chahiye?</strong> <br>
                            <a href="users/search.php" class="text-primary text-decoration-none fw-semibold">
                                <i class="bi bi-search-heart-fill me-1"></i> Donor dhoondo
                            </a>
                        </p>

                        <div class="d-grid gap-3 d-sm-flex justify-content-center mt-4">
                            <a href="users/register_donor.php" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus-fill me-2"></i> Register as Donor
                            </a>
                            <a href="users/search.php" class="btn btn-danger btn-lg">
                                <i class="bi bi-search-heart-fill me-2"></i> Find a Donor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
