<?php
// view_cultural_event.php

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("❌ Invalid event ID.");
}

$event_id = intval($_GET['event_id']);

// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "cems";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event
$stmt = $conn->prepare("SELECT * FROM cultural_events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Event not found.");
}

$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($event['event_name']) ?> - Details</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 30px;
            background: #f9f9f9;
        }
        .event-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
       .event-container img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
}

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="event-container">
        <h1><?= htmlspecialchars($event['event_name']) ?></h1>
        <img src="<?= htmlspecialchars($event['image_path']) ?>" alt="Event Image"><br><br>
         <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
    <p><strong>Paid:</strong> <?php echo $event['is_paid'] ? 'Yes' : 'No'; ?></p>
    <?php if ($event['is_paid']) echo "<p><strong>Amount:</strong> ₹" . htmlspecialchars($event['amount']) . "</p>"; ?>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <a class="btn" href="register_event.php?event_id=<?= $event['id'] ?>&type=Cultural">Register Now</a>
    </div>
</body>
</html>
