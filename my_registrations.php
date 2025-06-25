<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<h3 style='text-align:center; color:red;'>⚠️ Please login to view your registered events.</h3>";
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";
$conn = new mysqli($host, $user, $pass, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Fetch registrations
$stmt = $conn->prepare("SELECT * FROM event_registrations WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Registered Events</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fb;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
            transition: 0.3s ease-in-out;
            border-left: 5px solid transparent;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .event-type-tech {
            border-color: #007bff;
        }

        .event-type-cultural {
            border-color: #e83e8c;
        }

        .event-type-sport {
            border-color: #28a745;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }

        .paid {
            background-color: #28a745;
            color: white;
        }

        .pending {
            background-color: #ffc107;
            color: black;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 50px;
        }

        .event-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info {
            margin: 4px 0;
        }

        .icon {
            font-weight: bold;
            color: #444;
            margin-right: 6px;
        }
    </style>
</head>
<body>

<h2>🎟️ My Registered Events</h2>

<?php if ($result && $result->num_rows > 0): ?>
<div class="grid">
    <?php while ($row = $result->fetch_assoc()):
        $type = strtolower($row['event_type']);
        $status = strtolower($row['payment_status'] ?? 'Pending');
    ?>
    <div class="card event-type-<?= $type ?>">
        <div class="event-name"><?= htmlspecialchars($row['event_name']) ?></div>
        <div class="info"><span class="icon">📅 Type:</span> <?= htmlspecialchars($row['event_type']) ?></div>
        <div class="info"><span class="icon">🧑 Name:</span> <?= htmlspecialchars($row['name']) ?></div>
        <div class="info"><span class="icon">📧 Email:</span> <?= htmlspecialchars($row['email']) ?></div>
        <div class="info"><span class="icon">📞 Phone:</span> <?= htmlspecialchars($row['phone']) ?></div>
        <div class="info">
            <span class="icon">💰 Status:</span>
            <span class="badge <?= $status === 'paid' ? 'paid' : 'pending' ?>">
                <?= ucfirst($status) ?>
            </span>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
    <div class="no-data">You haven't registered for any events yet. 📭</div>
<?php endif; ?>

</body>
</html>
