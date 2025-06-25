<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name   = $_POST['event_name'];
    $event_type   = $_POST['event_type'];
    $event_date   = $_POST['event_date'];
    $description  = $_POST['description'];
    $is_paid      = isset($_POST['is_paid']) ? 1 : 0;
    $amount       = $is_paid ? $_POST['amount'] : 0;

    // Image upload handling
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = basename($_FILES["event_image"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["event_image"]["tmp_name"]);
    if ($check === false) {
        $message = "❌ File is not a valid image.";
    } else {
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            // Determine table
            if ($event_type == "Cultural") {
                $table = "cultural_events";
            } elseif ($event_type == "Tech") {
                $table = "tech_events";
            } elseif ($event_type == "Sports") {
                $table = "sport_events";
            } else {
                $message = "❌ Invalid event type.";
                exit;
            }

            // Prepare insert query
            $sql = "INSERT INTO $table (event_name, event_type, event_date, image_path, event_description, is_paid, amount) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("❌ Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssssssi", $event_name, $event_type, $event_date, $target_file, $description, $is_paid, $amount);

            if ($stmt->execute()) {
                $message = "✅ Event successfully uploaded to $table.";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Image upload failed.";
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
            font-family: 'Times New Roman', serif;
            background-color: #f4f6f8;
            padding: 30px;
        }

        h2 {
            text-align: center;
        }

        form {
            width: 40%;
            height: 20;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .checkbox-group label {
            margin-right: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        #amount_field {
            display: none;
        }
    </style>

    <script>
        function toggleAmountField() {
            const isPaid = document.getElementById('is_paid').checked;
            const amountField = document.getElementById('amount_field');
            amountField.style.display = isPaid ? 'block' : 'none';
        }
    </script>
</head>
<body>

<h2>Upload Event</h2>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" id="event_name" required>

    <label for="event_type">Event Type:</label>
    <select name="event_type" id="event_type" required>
        <option value="">-- Select Type --</option>
        <option value="Cultural">Cultural</option>
        <option value="Tech">Tech</option>
        <option value="Sports">Sports</option>
    </select>

    <label for="event_date">Event Date:</label>
    <input type="date" name="event_date" id="event_date" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="4" required></textarea>

    <div class="checkbox-group">
        <label for="is_paid">Is Paid Event?</label>
        <input type="checkbox" name="is_paid" id="is_paid" onchange="toggleAmountField()">
    </div>

    <div id="amount_field">
        <label for="amount">Amount (in ₹):</label>
        <input type="number" name="amount" id="amount" min="0">
    </div>

    <label for="event_image">Upload Image:</label>
    <input type="file" name="event_image" id="event_image" accept="image/*" required>

    <button type="submit">Upload Event</button>
</form>

<?php if (!empty($message)): ?>
    <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : ''; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<script>
    toggleAmountField(); // To apply on page load if already checked
</script>

</body>
</html>
