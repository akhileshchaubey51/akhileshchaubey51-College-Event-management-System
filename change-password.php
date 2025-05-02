<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$message_type = ""; // success or error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the existing password
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $db_password = $user['password'];
        $is_password_correct = false;

        if (password_verify($current_password, $db_password) || $current_password === $db_password) {
            $is_password_correct = true;
        }

        if ($is_password_correct) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $_SESSION['message'] = "Password updated successfully!";
                    session_destroy(); // logout user
                    header("Location: login.php?message=" . urlencode("Password changed successfully. Please login again."));
                    exit();
                } else {
                    $message = "Error updating password. Please try again.";
                    $message_type = "error";
                }
            } else {
                $message = "New password and confirm password do not match.";
                $message_type = "error";
            }
        } else {
            $message = "Current password is incorrect.";
            $message_type = "error";
        }
    } else {
        $message = "User not found.";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('https://pbs.twimg.com/media/EtdBwqlU0AEa-g1?format=jpg&name=4096x4096') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            max-width: 400px;
            margin: 80px auto;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: fadeIn 1s ease;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 28px;
        }

        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        input[type="password"] {
            background: rgba(255, 255, 255, 0.3);
            color: #000;
        }

        input[type="password"]::placeholder {
            color: #333;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            animation: fadeInMessage 1s ease;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #fff;
        }

        .logo {
            width: 200px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            animation: fadeIn 1.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInMessage {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 30px 20px;
                padding: 20px;
            }

            .logo {
                width: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="login.png" alt="Logo" class="logo">
        <h2>Change Password</h2>

        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password" required>

            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>

            <input type="submit" value="Change Password">
        </form>
    </div>

    <script>
        // Auto-hide message after 4 seconds
        setTimeout(() => {
            const message = document.querySelector('.message');
            if (message) {
                message.style.display = 'none';
            }
        }, 4000);
    </script>
</body>

</html>
