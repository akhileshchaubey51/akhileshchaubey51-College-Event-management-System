<?php
// events.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all events
$all_events = [];

$tech = $conn->query("SELECT id, event_name, image_path, 'Tech' as type, event_date FROM tech_events");
while ($row = $tech->fetch_assoc()) {
    $all_events[] = $row;
}

$cultural = $conn->query("SELECT id, event_name, image_path, 'Cultural' as type, event_date FROM cultural_events");
while ($row = $cultural->fetch_assoc()) {
    $all_events[] = $row;
}

$sport = $conn->query("SELECT id, event_name, image_path, 'Sport' as type, event_date FROM sport_events");
while ($row = $sport->fetch_assoc()) {
    $all_events[] = $row;
}

// Sort by event date
usort($all_events, function ($a, $b) {
    return strtotime($a['event_date']) <=> strtotime($b['event_date']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Events</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        h2 {
            color: #444;
            text-align: center;
        }

        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .event-box {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .event-box:hover {
            transform: scale(1.02);
        }

        .event-box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .event-box h3 {
            margin: 10px 0;
            color: #333;
            font-size: 18px;
        }

        .event-date {
            color: #666;
            font-size: 14px;
        }

        .btn-group {
            margin-top: 12px;
        }

        .btn {
            background: #007bff;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-right: 10px;
        }

        .btn.view {
            background: #28a745;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <h2>📅 Available Events</h2>

    <div class="events-container">
        <?php foreach ($all_events as $event): 
            $image = htmlspecialchars($event['image_path']);
            $name = htmlspecialchars($event['event_name']);
            $type = $event['type'];
            $id = $event['id'];
            $date = date("F j, Y", strtotime($event['event_date']));

            // Set view page according to event type
            $view_page = '';
            if ($type === 'Cultural') {
                $view_page = 'view_cultural_event.php';
            } elseif ($type === 'Tech') {
                $view_page = 'view_tech_event.php';
            } elseif ($type === 'Sport') {
                $view_page = 'view_sport_event.php';
            }
        ?>
            <div class="event-box">
                <img src="<?= !empty($image) ? $image : 'default.jpg' ?>" alt="Event Image">
                <h3><?= $name ?> (<?= $type ?>)</h3>
                <div class="event-date">📅 <?= $date ?></div>
                <div class="btn-group">
                    <a class="btn view" href="<?= $view_page ?>?event_id=<?= $id ?>">View Details</a>
                    <a class="btn" href="register_event.php?event_id=<?= $id ?>&type=<?= $type ?>">Register</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
