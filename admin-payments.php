<?php
$conn = new mysqli("localhost", "root", "", "cems");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM event_registrations WHERE payment_status = 'Pending Approval'";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Payment Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 60px;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8);
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }
        .close {
            position: absolute;
            top: 20px; right: 35px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light p-5">

<div class="container">
    <h2 class="mb-4">Pending Payment Approvals</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Event</th>
            <th>User</th>
            <th>Contact</th>
            <th>Amount</th>
            <th>Screenshot</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?><br><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <?php if (!empty($row['payment_screenshot'])): ?>
                            <img src="<?= $row['payment_screenshot'] ?>" width="100" style="cursor:pointer;" onclick="showModal('<?= $row['payment_screenshot'] ?>')">
                        <?php else: ?>
                            No Screenshot
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post" action="approve-payment.php" class="d-inline">
                            <input type="hidden" name="registration_id" value="<?= $row['id'] ?>">
                            <button name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="post" action="approve-payment.php" class="d-inline">
                            <input type="hidden" name="registration_id" value="<?= $row['id'] ?>">
                            <button name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No pending payments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Image modal -->
<div id="imgModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
function showModal(src) {
    document.getElementById('imgModal').style.display = "block";
    document.getElementById('modalImage').src = src;
}
function closeModal() {
    document.getElementById('imgModal').style.display = "none";
}
</script>
</body>
</html>
