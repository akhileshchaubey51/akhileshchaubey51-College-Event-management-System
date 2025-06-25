<?php
$conn = new mysqli("localhost", "root", "", "cems");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$registration_id = $_POST['registration_id'] ?? '';
$action = $_POST['action'] ?? '';

if ($registration_id && in_array($action, ['approve', 'reject'])) {
    $status = $action === 'approve' ? 'Paid' : 'Rejected';

    $stmt = $conn->prepare("UPDATE event_registrations SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $registration_id);
    $stmt->execute();
}

header("Location: admin-payments.php");
exit();
