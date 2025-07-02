<?php
include '../db_connect.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Notify Donors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #FFA07A;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center fs-4" href="monitor_requests.php">
                <img src="../images/logo.png" alt="Logo" width="45" height="45" class="me-2">
                <span class="fw-bold">Admin Dashboard</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
                <ul class="navbar-nav fs-5">

                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="monitor_requests.php">🩸 Monitor Requests</a>
                    </li>

                    <li class="nav-item px-2">
                        <a class="nav-link text-white" href="approve_donors.php">✔️ Approve Donors</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="../logout.php">
                            <i class="bi bi-box-arrow-right text-warning me-1"></i> Logout
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">📢 Donor Notification Log</h2>

        <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $blood_group = $_POST['blood_group'];
        $city = $_POST['city'];

        // Select matching donors
        $stmt = $conn->prepare("SELECT * FROM donors WHERE blood_group = ? AND city = ?");
        $stmt->bind_param("ss", $blood_group, $city);
        $stmt->execute();
        $result = $stmt->get_result();

        $count = 0;

        if ($result->num_rows > 0) {
            echo '<div class="list-group mb-4">';
            while ($donor = $result->fetch_assoc()) {
                $count++;
                echo '
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-dark text-light">
                        <div>
                            <h5 class="mb-1 text-success">✅ ' . htmlspecialchars($donor['name']) . '</h5>
                            <p class="mb-1">📞 ' . htmlspecialchars($donor['phone']) . ' &nbsp; | &nbsp; 📧 ' . htmlspecialchars($donor['email']) . '</p>
                        </div>
                        <span class="badge bg-primary">Notified</span>
                    </div>
                ';
            }
            echo '</div>';

            // ✅ Dismissible success alert
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
              <strong>$count donors notified successfully.</strong>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        } else {
            // ❌ Dismissible warning alert
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
              😕 No matching donors found in <strong>" . htmlspecialchars($city) . "</strong> 
              for blood group <strong>" . htmlspecialchars($blood_group) . "</strong>.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    } else {
        echo "
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          ❌ Invalid request method.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    ?>
    </div>

    <!-- Bootstrap Bundle JS (for dismissible alerts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>