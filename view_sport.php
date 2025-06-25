<?php
include('includes/config.php');
session_start();

$eventName = isset($_GET['event_name']) ? trim($_GET['event_name']) : '';

if (!$eventName) {
    echo "<h2 style='font-family: Times New Roman; font-size: 28px; color: red;'>Invalid event name.</h2>";
    exit();
}

try {
    $stmt = $dbh->prepare("SELECT * FROM sport_events WHERE event_name = :event_name LIMIT 1");
    $stmt->bindParam(':event_name', $eventName);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "<h2 style='font-family: Times New Roman; font-size: 28px; color: red;'>Event not found.</h2>";
        exit();
    }
} catch (PDOException $e) {
    echo "<h2 style='font-family: Times New Roman; font-size: 28px; color: red;'>Database error: " . $e->getMessage() . "</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Sport Event</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 25px;
        }
        .event-image {
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        p {
            font-size: 20px;
            line-height: 1.8;
            color: #333;
        }
        .back-link {
            display: block;
            margin-top: 30px;
            text-align: center;
            font-size: 18px;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
    <div class="event-image" style="background-image: url('<?php echo htmlspecialchars($event['image_path']); ?>');"></div>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
    <p><strong>Paid:</strong> <?php echo $event['is_paid'] ? 'Yes' : 'No'; ?></p>
    <?php if ($event['is_paid']) echo "<p><strong>Amount:</strong> ₹" . htmlspecialchars($event['amount']) . "</p>"; ?>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    <a class="back-link" href="sport_events.php">← Back to Sport Events</a>
</div>
</body>
</html>
