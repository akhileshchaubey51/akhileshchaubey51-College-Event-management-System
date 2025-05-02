<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $event_date = $_POST['event_date'];

    // Image Upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = basename($_FILES["event_image"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["event_image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
    } else {
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            // Decide table name based on event type
            if ($event_type == "Cultural") {
                $table = "cultural_events";
            } elseif ($event_type == "Tech") {
                $table = "tech_events";
            } elseif ($event_type == "Sports") {
                $table = "sport_events";
            } else {
                $message = "❌ Invalid event type selected.";
                exit;
            }

            // Prepare SQL query
            $sql = "INSERT INTO $table (event_name, event_type, event_date, image_path) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssss", $event_name, $event_type, $event_date, $target_file);

            if ($stmt->execute()) {
                $message = "✅ Event uploaded successfully into $table!";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Error uploading image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            padding: 30px;
        }

        h2 {
            text-align: center;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: green;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<h2>Upload Event</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" id="event_name" required>

    <label for="event_type">Event Type:</label>
    <select name="event_type" id="event_type" required>
        <option value="Cultural">Cultural</option>
        <option value="Tech">Tech</option>
        <option value="Sports">Sports</option>
    </select>

    <label for="event_date">Event Date:</label>
    <input type="date" name="event_date" id="event_date" required>

    <label for="event_image">Upload Image:</label>
    <input type="file" name="event_image" id="event_image" accept="image/*" required>

    <button type="submit">Upload Event</button>
</form>

<?php if (!empty($message)): ?>
    <div class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : ''; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

</body>
</html>
