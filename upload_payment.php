<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$status = "";
$registration_id = $_GET['registration_id'] ?? '';
$event_type = '';
$amount = 0.00;

// Step 1: Get event_type and event_id from registrations
if (!empty($registration_id)) {
    $stmt = $conn->prepare("SELECT event_type, event_id FROM event_registrations WHERE id = ?");
    $stmt->bind_param("i", $registration_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $event_type = $row['event_type'];
        $event_id = $row['event_id'];

        // Step 2: Get the correct table name based on event_type
        $event_table = getEventTableByType($event_type);

        // Step 3: Fetch amount from the corresponding event table
        $stmt2 = $conn->prepare("SELECT amount FROM $event_table WHERE id = ?");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        if ($row2 = $result2->fetch_assoc()) {
            $amount = $row2['amount'];
        }
    }
}

// Helper function to determine table name
function getEventTableByType($event_type) {
    switch (strtolower($event_type)) {
        case 'tech':
            return 'tech_events';
        case 'cultural':
            return 'cultural_events';
        case 'sport':
            return 'sport_events';
        default:
            return 'cultural_events'; // default fallback
    }
}

// Payment upload handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_screenshot'])) {
    $registration_id = $_POST['registration_id'];
    $target_dir = "payment_screenshots/";
    $file_name = basename($_FILES["payment_screenshot"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["payment_screenshot"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("UPDATE event_registrations SET payment_screenshot = ?, payment_status = 'Pending Approval' WHERE id = ?");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("si", $target_file, $registration_id);
        if ($stmt->execute()) {
            $message = "✅ Payment successful!";
            $status = "success";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'dashboard.php';
                    }, 2000);
                  </script>";
        } else {
            $message = "❌ Failed to update payment record.";
            $status = "error";
        }
    } else {
        $message = "❌ Failed to upload file.";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Payment Screenshot</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 40px;
            background-color: #f0f2f5;
        }
        .container {
            width: 420px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 15px;
        }
        img.qr {
            max-width: 200px;
            margin: 20px auto;
        }
        .amount-box {
            font-size: 18px;
            margin: 10px 0 20px;
            background: #e9f7ef;
            padding: 10px;
            border: 1px solid #28a745;
            border-radius: 8px;
        }
        input[type="file"], button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 250px;
            padding: 15px;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 9999;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
	/* For smaller devices like smartphones */
@media (max-width: 480px) {
    .heading1 {
        font-size: 70px;
    }

    .heading2 {
        font-size: 24px;
    }

    .page2-head {
        font-size: 30px;
    }

    .page2-content .page2-box {
        width: 100%;
        margin: 10px 0;
        height: 25vh;
    }

    .page1 .navbar ul li {
        font-size: 16px;
    }

    .navbar button {
        font-size: 16px;
        width: 70px;
        height: 30px;
    }
}
    </style>
</head>
<body>
<div class="container">
    <h2>Scan to Pay</h2>
    <img src="qr-code.jpg" alt="QR Code" class="qr">
    
    <?php if ($amount > 0): ?>
        <div class="amount-box">
            <strong>Amount to Pay:</strong> ₹<?= number_format($amount, 2) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="registration_id" value="<?= htmlspecialchars($registration_id) ?>">
        <label><strong>Upload Payment Screenshot:</strong></label>
        <input type="file" name="payment_screenshot" accept="image/*" required>
        <button type="submit">Upload & Confirm</button>
    </form>
</div>

<?php if (!empty($message)): ?>
<div class="toast <?= $status ?>"><?= $message ?></div>
<?php endif; ?>
</body>
</html>
