<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CEMS";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
}

header('Content-Type: application/json');

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($event_id > 0) {
    // Fetch event name
    $stmt = $conn->prepare("SELECT event_name FROM tech_events WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->bind_result($event_name);
        if ($stmt->fetch()) {
            echo json_encode(["name" => $event_name]);
        } else {
            echo json_encode(["error" => "Event not found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare statement."]);
    }
} else {
    echo json_encode(["error" => "Invalid event ID."]);
}

$conn->close();
?>
