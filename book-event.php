<?php
include 'config.php';
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if event_id is provided
if (isset($_POST['event_id'])) {
    $user_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);

    // Check if the event exists
    $event_check = $conn->prepare("SELECT * FROM events WHERE id = ?");
    if ($event_check) {
        $event_check->bind_param("i", $event_id);
        $event_check->execute();
        $event_result = $event_check->get_result();

        if ($event_result->num_rows === 0) {
            echo "<div class='error'>❌ Event not found.</div>";
            exit();
        }

        $event = $event_result->fetch_assoc();
    } else {
        echo "<div class='error'>❌ Error: " . $conn->error . "</div>";
        exit();
    }

    // Check if user has already booked this event
    $check_stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? AND event_id = ?");
    if ($check_stmt) {
        $check_stmt->bind_param("ii", $user_id, $event_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<div class='warning'>⚠️ You have already booked this event.</div>";
            exit();
        }
    } else {
        echo "<div class='error'>❌ Error: " . $conn->error . "</div>";
        exit();
    }

    // Handle Paid Events
    if ($event['is_paid'] == 1) {
        $price = $event['price'];

        if ($price <= 0) {
            echo "<div class='error'>❌ Invalid event price.</div>";
            exit();
        }

        echo "<div class='payment-box'>";
        echo "<h3 class='payment-title'>💳 Payment Required</h3>";
        echo "<p>Event: <strong>" . htmlspecialchars($event['title']) . "</strong></p>";
        echo "<p>Price: ₹" . htmlspecialchars($price) . "</p>";
        echo "<form method='POST' action='process_payment.php'>";
        echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
        echo "<input type='hidden' name='price' value='" . $price . "'>";
        echo "<button type='submit' class='pay-button'>💳 Pay & Book</button>";
        echo "</form>";
        echo "</div>";
        exit();
    } else {
        // For free events, insert booking with new attributes
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, booking_date, status) VALUES (?, ?, NOW(), ?)");
        $status = 'Confirmed';

        if ($stmt) {
            $stmt->bind_param("iis", $user_id, $event_id, $status);
            if ($stmt->execute()) {
                echo "<div class='success'>✅ Free event booked successfully! <a href='my_bookings.php'>View My Bookings</a></div>";
            } else {
                echo "<div class='error'>❌ Booking failed: " . $stmt->error . "</div>";
            }
        } else {
            echo "<div class='error'>❌ Error: " . $conn->error . "</div>";
        }
    }
} else {
    echo "<div class='error'>❌ No event selected for booking.</div>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f4f8;
    text-align: center;
    padding: 20px;
}

.payment-box, .success, .error, .warning {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 400px;
    margin: 20px auto;
    animation: fadeIn 0.5s ease;
}

.payment-title {
    font-size: 24px;
    margin-bottom: 10px;
}

.pay-button {
    background: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.pay-button:hover {
    background: #45a049;
}

.success {
    border-left: 5px solid #4CAF50;
    color: #4CAF50;
}

.error {
    border-left: 5px solid #f44336;
    color: #f44336;
}

.warning {
    border-left: 5px solid #ff9800;
    color: #ff9800;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
