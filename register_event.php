<?php
session_start();

// 🔐 Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cems";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_type = $_GET['type'] ?? '';
$selected_id = $_GET['event_id'] ?? '';
$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'] ?? '';
    $event_type = $_POST['event_type'] ?? '';
    $members = $_POST['members'] ?? [];

    if (empty($event_id) || empty($event_type) || empty($members)) {
        $message = "❌ Please fill in all required fields.";
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
            $stmt_check = $conn->prepare("SELECT id, event_name, is_paid, amount FROM $table WHERE id = ?");
            $stmt_check->bind_param("i", $event_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $event = $result_check->fetch_assoc();
                $event_name = $event['event_name'];
                $is_paid = $event['is_paid'];
                $amount = $event['amount'];

                $valid = true;
                foreach ($members as $member) {
                    if (!preg_match("/@gehu\.ac\.in$/", $member['email']) || !preg_match("/^\d{10}$/", $member['phone'])) {
                        $valid = false;
                        break;
                    }
                }

                if (!$valid) {
                    $message = "❌ All emails must end with @gehu.ac.in and phone numbers must be 10 digits.";
                    $status = "error";
                } else {
                    $leader = $members[0];

                    $check_duplicate = $conn->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND email = ?");
                    $check_duplicate->bind_param("is", $event_id, $leader['email']);
                    $check_duplicate->execute();
                    $result_duplicate = $check_duplicate->get_result();

                    if ($result_duplicate->num_rows > 0) {
                        $message = "❌ You have already registered for this event.";
                        $status = "error";
                    } else {
                        $stmt_insert = $conn->prepare("INSERT INTO event_registrations (event_id, event_type, event_name, name, email, phone, amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt_insert->bind_param("isssssd", $event_id, $event_type, $event_name, $leader['name'], $leader['email'], $leader['phone'], $amount);

                        if ($stmt_insert->execute()) {
                            $registration_id = $stmt_insert->insert_id;

                            foreach ($members as $member) {
                                $stmt_member = $conn->prepare("INSERT INTO registration_members (registration_id, name, email, phone) VALUES (?, ?, ?, ?)");
                                $stmt_member->bind_param("isss", $registration_id, $member['name'], $member['email'], $member['phone']);
                                $stmt_member->execute();
                            }

                            if ($is_paid == 1) {
                                header("Location: upload_payment.php?registration_id=$registration_id");
                                exit;
                            } else {
                                $message = "✅ Registration successful!";
                                $status = "success";
                                echo "<script>setTimeout(() => window.location.href = 'dashboard.php', 2000);</script>";
                            }
                        } else {
                            $message = "❌ Error inserting registration: " . $stmt_insert->error;
                            $status = "error";
                        }
                    }
                }
            } else {
                $message = "❌ Event not found.";
                $status = "error";
            }
        }
    }
}

$tech_events_result = $conn->query("SELECT id, event_name FROM tech_events");
$cultural_events_result = $conn->query("SELECT id, event_name FROM cultural_events");
$sport_events_result = $conn->query("SELECT id, event_name FROM sport_events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Event</title>
    <style>
        body {
            font-family: Arial;
            background: #f1f1f1;
            padding: 30px;
        }
        form {
            background: white;
            padding: 30px;
            width: 650px;
            margin: auto;
            border-radius: 14px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }
        .member-box {
            background: #f9f9f9;
            padding: 15px;
            margin-top: 15px;
            border-radius: 10px;
            border: 1px dashed #ccc;
            position: relative;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            color: white;
            border-radius: 6px;
            z-index: 9999;
        }
        .success { background: green; }
        .error { background: crimson; }
    </style>
</head>
<body>

<form method="POST">
    <h2>🧑‍🤝‍🧑 Register for Event</h2>

    <label for="event_type">Event Type</label>
    <select name="event_type" id="event_type" required onchange="showEvents()">
        <option value="">-- Select --</option>
        <option value="Tech" <?= $selected_type == 'Tech' ? 'selected' : '' ?>>Tech</option>
        <option value="Cultural" <?= $selected_type == 'Cultural' ? 'selected' : '' ?>>Cultural</option>
        <option value="Sport" <?= $selected_type == 'Sport' ? 'selected' : '' ?>>Sport</option>
    </select>

    <label for="event_id">Event</label>
    <select name="event_id" id="event_id" required>
        <option value="">-- Select --</option>
        <optgroup label="Tech Events" id="tech-events" style="display:none;">
            <?php while ($row = $tech_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($selected_type === 'Tech' && $selected_id == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['event_name']) ?>
                </option>
            <?php endwhile; ?>
        </optgroup>
        <optgroup label="Cultural Events" id="cultural-events" style="display:none;">
            <?php while ($row = $cultural_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($selected_type === 'Cultural' && $selected_id == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['event_name']) ?>
                </option>
            <?php endwhile; ?>
        </optgroup>
        <optgroup label="Sport Events" id="sport-events" style="display:none;">
            <?php while ($row = $sport_events_result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($selected_type === 'Sport' && $selected_id == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['event_name']) ?>
                </option>
            <?php endwhile; ?>
        </optgroup>
    </select>

    <label>Team Members</label>
    <div id="membersContainer"></div>
    <button type="button" onclick="addMember()">➕ Add Member</button>

    <button type="submit">✅ Submit Registration</button>
</form>

<?php if (!empty($message)): ?>
    <div class="toast <?= $status ?>"><?= $message ?></div>
<?php endif; ?>

<script>
let memberIndex = 0;

function showEvents() {
    document.getElementById('tech-events').style.display = 'none';
    document.getElementById('cultural-events').style.display = 'none';
    document.getElementById('sport-events').style.display = 'none';

    let type = document.getElementById('event_type').value;
    if (type === 'Tech') document.getElementById('tech-events').style.display = 'block';
    if (type === 'Cultural') document.getElementById('cultural-events').style.display = 'block';
    if (type === 'Sport') document.getElementById('sport-events').style.display = 'block';
}

function addMember(name = '', email = '', phone = '') {
    const container = document.getElementById('membersContainer');
    const memberHTML = `
        <div class="member-box" id="member-${memberIndex}">
            <h4>Member ${memberIndex + 1}
                <button type="button" onclick="removeMember(${memberIndex})" style="float:right;">❌</button>
            </h4>
            <label>Name:</label>
            <input type="text" name="members[${memberIndex}][name]" value="${name}" required>

            <label>Email (@gehu.ac.in):</label>
            <input type="email" name="members[${memberIndex}][email]" pattern="[^@\\s]+@gehu\\.ac\\.in" value="${email}" required>

            <label>Phone:</label>
            <input type="text" name="members[${memberIndex}][phone]" pattern="\\d{10}" maxlength="10" value="${phone}" required>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', memberHTML);
    memberIndex++;
}

function removeMember(index) {
    const box = document.getElementById(`member-${index}`);
    if (box) box.remove();
}

window.onload = () => {
    showEvents();
    addMember("<?= $_SESSION['user_name'] ?? '' ?>", "<?= $_SESSION['user_email'] ?? '' ?>", "<?= $_SESSION['user_phone'] ?? '' ?>");
};
</script>

</body>
</html>
