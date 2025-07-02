<?php
session_start();
require_once '../db_connect.php';

// ✅ Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Handle Approve/Reject form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donor_id'], $_POST['action'])) {
    $donor_id = intval($_POST['donor_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE donors SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $donor_id);
    $stmt->execute();
    $stmt->close();

    header("Location: approve_donors.php");
    exit;
}

// ✅ Fetch all pending donors
$sql = "SELECT * FROM donors WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pending Donor Approvals</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../js/donor-actions.js" defer></script>
</head>

<body style="background-color: #FFA07A;">

    <!-- ✅ Navbar -->
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
                        <a class="nav-link text-white" href="notify_donors.php">📢 Notify Donors</a>
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

    <!-- ✅ Main Section -->
    <div class="container mt-5">
        <h2 class="mb-4">Pending Donor Approvals</h2>

        <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered table-hover bg-white shadow">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['age'] ?></td>
                    <td><?= $row['gender'] ?></td>
                    <td><?= $row['blood_group'] ?></td>
                    <td><?= $row['city'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <button class="btn btn-success btn-sm me-1 open-modal" data-bs-toggle="modal"
                            data-bs-target="#actionModal" data-id="<?= $row['id'] ?>" data-action="approve"
                            data-name="<?= htmlspecialchars($row['name']) ?>">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                        <button class="btn btn-danger btn-sm open-modal" data-bs-toggle="modal"
                            data-bs-target="#actionModal" data-id="<?= $row['id'] ?>" data-action="reject"
                            data-name="<?= htmlspecialchars($row['name']) ?>">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="alert alert-info">No pending donors found.</div>
        <?php endif; ?>
    </div>

    <!-- ✅ Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="approve_donors.php">
                <input type="hidden" name="donor_id" id="modalDonorId">
                <input type="hidden" name="action" id="modalAction">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actionModalLabel">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalMessage">
                        <!-- Message filled by JS -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="modalSubmitBtn">Yes, Continue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>