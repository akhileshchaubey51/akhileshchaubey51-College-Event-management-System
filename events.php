<?php
session_start();
include 'config.php';

// Get event type from URL
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

$sql = "SELECT * FROM events";
if ($type != 'all') {
    $sql .= " WHERE category = '" . $conn->real_escape_string($type) . "'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f2f2f2;
        }
        .event-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Upcoming 
            <?php 
                if ($type == 'tech') echo "Tech Events";
                elseif ($type == 'cultural') echo "Cultural Events";
                elseif ($type == 'sport') echo "Sport Events";
                else echo "All Events";
            ?>
        </h1>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="card event-card">
                        <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Event Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo $row['date']; ?></p>
                            <a href="book_event.php?event_id=<?php echo $row['id']; ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
