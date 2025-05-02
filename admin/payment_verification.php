<?php
// admin/payment_verification.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submission_id'], $_POST['status'])) {
    $submission_id = (int)$_POST['submission_id'];
    $status = $_POST['status'] === 'Approved' ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE payment_submissions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $submission_id);
    $stmt->execute();
}

// Fetch submissions
$sql = "SELECT ps.id, ps.registration_id, ps.image_path, ps.status, ps.submitted_at,
        er.name, er.email, er.event_name
        FROM payment_submissions ps
        JOIN event_registrations er ON ps.registration_id = er.id
        ORDER BY ps.submitted_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        img {
            max-height: 100px;
        }
        form {
            display: inline;
        }
        select, button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<h2>Payment Screenshot Verification</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Registrant</th>
            <th>Email</th>
            <th>Event</th>
            <th>Screenshot</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['event_name']) ?></td>
            <td><img src="../<?= htmlspecialchars($row['image_path']) ?>" alt="Screenshot"></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['submitted_at'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                    <select name="status">
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
