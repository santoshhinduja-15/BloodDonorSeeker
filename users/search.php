<?php
session_start();
include('../db_connect.php'); // Your DB connection file

// ✅ Define user email before using in navbar
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;

$blood_group = $city = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    $sql = "SELECT name, blood_group, city, phone 
            FROM donors 
            WHERE blood_group = '$blood_group' AND city LIKE '%$city%'";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Donors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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

    <!-- ✅ SEARCH FORM & RESULTS -->
    <div class="container mt-5">
        <h2 class="mb-4 text-center">
            <i class="bi bi-search-heart text-danger"></i> Search Blood Donors
        </h2>

        <form method="POST" class="row g-3 shadow p-4 bg-white rounded">
            <div class="col-md-4">
                <label for="blood_group" class="form-label">
                    <i class="bi bi-droplet-half text-danger"></i> Blood Group
                </label>
                <select name="blood_group" id="blood_group" class="form-select" required>
                    <option value="">Choose...</option>
                    <?php
                $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                foreach ($groups as $group) {
                    $selected = ($blood_group == $group) ? 'selected' : '';
                    echo "<option value=\"$group\" $selected>$group</option>";
                }
                ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="city" class="form-label">
                    <i class="bi bi-geo-alt-fill text-primary"></i> City or State
                </label>
                <input type="text" name="city" id="city" class="form-control"
                    value="<?php echo htmlspecialchars($city); ?>" required>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <hr class="mt-5 mb-4">
        <h4 class="mb-3">
            <i class="bi bi-people-fill text-success"></i> Search Results
        </h4>

        <?php if (count($results) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><i class="bi bi-person-fill"></i> Name</th>
                        <th><i class="bi bi-droplet-fill"></i> Blood Group</th>
                        <th><i class="bi bi-geo-fill"></i> Location</th>
                        <th><i class="bi bi-telephone-fill"></i> Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $donor): ?>
                    <tr>
                        <td><?= htmlspecialchars($donor['name']) ?></td>
                        <td><?= htmlspecialchars($donor['blood_group']) ?></td>
                        <td><?= htmlspecialchars($donor['city']) ?></td>
                        <td>
                            <a href="tel:<?= htmlspecialchars($donor['phone']) ?>" class="text-decoration-none">
                                <i class="bi bi-telephone-forward-fill text-primary"></i>
                                <?= htmlspecialchars($donor['phone']) ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-circle-fill"></i> No donors found matching your criteria.
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (optional for navbar toggle) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>