<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = trim($_POST['feedback']);
    $user_id = $_SESSION['user_id'];

    if (!empty($feedback)) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, comments, feedback_date) VALUES (?, ?, NOW())");

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);  // Show detailed error
        }

        $stmt->bind_param("is", $user_id, $feedback);

        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error submitting feedback.'); window.location.href='dashboard.php';</script>";
        }

        $stmt->close();
    }
}
?>
