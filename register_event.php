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

$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $event_type = $_POST['event_type'] ?? '';

    if (empty($event_id) || empty($name) || empty($email) || empty($phone) || empty($event_type)) {
        $message = "❌ Please fill in all the required fields.";
        $status = "error";
    } elseif (!preg_match("/@gehu\.ac\.in$/", $email)) {
        $message = "❌ Only '@gehu.ac.in' emails are allowed.";
        $status = "error";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $message = "❌ Phone number must be exactly 10 digits.";
        $status = "error";
    } else {
        $table = match($event_type) {
            'Tech' => 'tech_events',
            'Cultural' => 'cultural_events',
            'Sport' => 'sport_events',
            default => null
        };

        if ($table === null) {
            $message = "❌ Invalid event type.";
            $status = "error";
        } else {
            $stmt_check = $conn->prepare("SELECT id, event_name, is_paid FROM $table WHERE id = ?");
            $stmt_check->bind_param("i", $event_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $event = $result_check->fetch_assoc();
                $event_name = $event['event_name'];
                $is_paid = $event['is_paid'];

                $stmt_check_reg = $conn->prepare("SELECT * FROM event_registrations WHERE event_id = ? AND email = ?");
                $stmt_check_reg->bind_param("is", $event_id, $email);
                $stmt_check_reg->execute();
                $result_check_reg = $stmt_check_reg->get_result();

                if ($result_check_reg->num_rows > 0) {
                    $message = "❌ You have already registered for this event.";
                    $status = "error";
                } else {
                    $stmt_insert = $conn->prepare("INSERT INTO event_registrations (event_id, event_type, event_name, name, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt_insert->bind_param("isssss", $event_id, $event_type, $event_name, $name, $email, $phone);

                    if ($stmt_insert->execute()) {
                        $registration_id = $stmt_insert->insert_id;

                        if ($is_paid == 1) {
                            // Redirect to payment page for paid events
                            header("Location: upload_payment.php?registration_id=" . $registration_id);
                            exit;
                        } else {
                            // If it's a free event, show success and redirect to dashboard after 2 seconds
                            $message = "✅ Registration successful! This is a free event.";
                            $status = "success";
                            echo "<script>
                                    setTimeout(function() {
                                        window.location.href = 'dashboard.php';
                                    }, 2000); // Redirect after 2 seconds
                                  </script>";
                        }
                    } else {
                        $message = "❌ Error: " . $stmt_insert->error;
                        $status = "error";
                    }
                }
            } else {
                $message = "❌ Event not found in selected category.";
                $status = "error";
            }
        }
    }
}

// Fetch events
$tech_events_result = $conn->query("SELECT id, event_name FROM tech_events");
$cultural_events_result = $conn->query("SELECT id, event_name FROM cultural_events");
$sport_events_result = $conn->query("SELECT id, event_name FROM sport_events");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
    <style>
         body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        form {
            width: 480px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin-top: 12px;
            font-weight: 600;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #007bff;
            box-shadow: 0 0 3px rgba(0,123,255,0.5);
        }

        button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            margin-top: 25px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: -400px;
            min-width: 280px;
            padding: 15px;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            transition: right 0.5s ease-in-out;
        }

        .toast.show {
            right: 20px;
        }

        .toast.success {
            background-color: #28a745;
        }

        .toast.error {
            background-color: #dc3545;
        }

        .note {
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<h2>🎫 Register for an Event</h2>

<form method="POST" action="" onsubmit="return validateForm();">
    <label>Event Type:</label>
    <select name="event_type" id="event_type" required>
        <option value="">-- Select Event Type --</option>
        <option value="Tech" <?= ($_POST['event_type'] ?? '') == 'Tech' ? 'selected' : '' ?>>Tech</option>
        <option value="Cultural" <?= ($_POST['event_type'] ?? '') == 'Cultural' ? 'selected' : '' ?>>Cultural</option>
        <option value="Sport" <?= ($_POST['event_type'] ?? '') == 'Sport' ? 'selected' : '' ?>>Sport</option>
    </select>

    <label>Event:</label>
    <select name="event_id" id="event_id" required>
        <option value="">-- Select Event --</option>
        <optgroup label="Tech Events" id="tech-events" style="display:none;">
            <?php while ($row = $tech_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['event_name'] ?></option>
            <?php endwhile; ?>
        </optgroup>
        <optgroup label="Cultural Events" id="cultural-events" style="display:none;">
            <?php while ($row = $cultural_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['event_name'] ?></option>
            <?php endwhile; ?>
        </optgroup>
        <optgroup label="Sport Events" id="sport-events" style="display:none;">
            <?php while ($row = $sport_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['event_name'] ?></option>
            <?php endwhile; ?>
        </optgroup>
    </select>

    <label>Your Name:</label>
    <input type="text" name="name" required value="<?= $_POST['name'] ?? '' ?>">

    <label>Email (use @gehu.ac.in):</label>
    <input type="email" name="email" id="email" required value="<?= $_POST['email'] ?? '' ?>">
    <div class="note">Only gehu emails allowed</div>

    <label>Phone:</label>
    <input type="text" name="phone" id="phone" required maxlength="10" value="<?= $_POST['phone'] ?? '' ?>">
    <div class="note">Enter 10-digit phone number</div>

    <button type="submit" id="submitBtn">Register</button>
</form>

<?php if (!empty($message)): ?>
    <div class="toast <?= $status ?> show" id="toastBox"><?= $message ?></div>
<?php endif; ?>

<script>
    // Show relevant events on type change
    document.getElementById('event_type').addEventListener('change', function () {
        document.getElementById('tech-events').style.display = 'none';
        document.getElementById('cultural-events').style.display = 'none';
        document.getElementById('sport-events').style.display = 'none';

        const type = this.value;
        if (type === 'Tech') document.getElementById('tech-events').style.display = 'block';
        else if (type === 'Cultural') document.getElementById('cultural-events').style.display = 'block';
        else if (type === 'Sport') document.getElementById('sport-events').style.display = 'block';
    });

    // Auto-show correct events after form error
    window.onload = function () {
        const toast = document.getElementById('toastBox');
        if (toast) {
            setTimeout(() => toast.classList.remove('show'), 3500);
        }

        let type = document.getElementById('event_type').value;
        if (type === 'Tech') document.getElementById('tech-events').style.display = 'block';
        else if (type === 'Cultural') document.getElementById('cultural-events').style.display = 'block';
        else if (type === 'Sport') document.getElementById('sport-events').style.display = 'block';
    };

    // Basic front-end validation
    function validateForm() {
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;

        if (!/@gehu\.ac\.in$/.test(email)) {
            alert("❌ Please use your gehu email (@gehu.ac.in).");
            return false;
        }

        if (!/^\d{10}$/.test(phone)) {
            alert("❌ Phone number must be exactly 10 digits.");
            return false;
        }

        // Optionally: disable button to prevent multiple submissions
        document.getElementById('submitBtn').innerText = "Submitting...";
        document.getElementById('submitBtn').disabled = true;
        return true;
    }
</script>

</body>
</html>