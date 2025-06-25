<?php
include('includes/config.php');
session_start();

// Check if the event ID is set in the URL
if (isset($_GET['event_id']) && isset($_GET['event_type']) && $_GET['event_type'] === 'Tech') {
    $event_id = $_GET['event_id'];

    try {
        // Fetch event details from the database based on the event_id
        $stmt = $dbh->prepare("SELECT * FROM tech_events WHERE id = :event_id");
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the event exists
        if (!$event) {
            echo "Event not found.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid event.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>EMS - Event Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        #event-page {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .event-image {
            width: 100%;
            height: 400px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
        }

        .event-details {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 30px;
        }

        .event-details h2 {
            color: #333;
        }

        .event-details p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .register-btn {
            background: #17a2b8;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
            display: inline-block;
        }

        .register-btn:hover {
            background: #138496;
        }

        .back-btn {
            background: #ccc;
            color: black;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
        }

        .back-btn:hover {
            background: #bbb;
        }
    </style>
</head>

<body>
    <div id="event-page">
        <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>

        <!-- Event Image -->
        <div class="event-image" style="background-image: url('<?php echo htmlspecialchars($event['image_path']); ?>');"></div>

        <!-- Event Details -->
        <div class="event-details">
            <h2>Event Description</h2>
            <p><?php echo nl2br(htmlspecialchars($event['title'])); ?></p>
            <h3>Event Category: <?php echo htmlspecialchars($event['category']); ?></h3>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($event['event_time']); ?></p>
            <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>

            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="register-btn" onclick="window.location.href='register_event.php?event_name=<?php echo urlencode($event['event_name']); ?>&event_type=Tech'">Register for Event</button>
                <?php else: ?>
                    <button class="register-btn" onclick="window.location.href='login.php'">Login to Register</button>
                <?php endif; ?>
            </div>
        </div>

        <button class="back-btn" onclick="window.history.back()">Back to Events</button>
    </div>
</body>
</html>
