<?php
include '../db_connect.php';

// Fetch blood requests (Urgent first, then by date)
$sql = "SELECT * FROM blood_requests 
        ORDER BY 
            CASE urgency 
                WHEN 'Urgent' THEN 1 
                ELSE 2 
            END, 
            request_date ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Blood Requests Monitor</title>
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
                        <a class="nav-link text-white" href="approve_donors.php">✔️ Approve Donors</a>
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

    <div class="container">
        <h2 class="mt-4">🩸 Blood Requests Monitor</h2>

        <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Blood Group</th>
                    <th>City</th>
                    <th>Urgency</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Reason</th>
                    <th>Requested</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= $row['blood_group'] ?></td>
                    <td><?= $row['city'] ?></td>
                    <td>
                        <span class="badge <?= $row['urgency'] == 'Urgent' ? 'bg-danger' : 'bg-secondary' ?>">
                            <?= $row['urgency'] ?>
                        </span>
                    </td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= nl2br(htmlspecialchars($row['reason'])) ?></td>
                    <td><?= $row['request_date'] ?></td>
                    <td>
                        <form action="notify_donors.php" method="post">
                            <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                            <input type="hidden" name="city" value="<?= $row['city'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Notify Donors
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted">No blood requests found.</p>
        <?php endif; ?>
    </div>
</body>

</html>