<?php
session_start();
$msg = '';
$redirectURL = isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php';

// Database connection
$host = "localhost";
$db = "cems";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $message = trim(htmlspecialchars($_POST['message']));

    // Server-side validation
    if (empty($name) || empty($email) || empty($message)) {
        $msg = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
        $msg = "Name must be 3-50 characters and only contain letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } elseif (!preg_match("/@gehu\.ac\.in$/", $email)) {
        $msg = "Only GEHU email addresses (@gehu.ac.in) are allowed.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $msg = "Thank you, your message has been received!";
        } else {
            $msg = "Error: Could not save your message. Please try again later.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GEHU - Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <?php if ($msg && strpos($msg, 'Thank you') !== false): ?>
        <meta http-equiv="refresh" content="2;url=<?php echo $redirectURL; ?>">
    <?php endif; ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            color: #333;
        }
        .container { max-width: 1200px; margin: auto; padding: 40px 20px; }
        .heading { text-align: center; padding: 30px 0; }
        .heading h1 { font-size: 3rem; color: #00796b; font-weight: 600; }

        .contact-content {
            display: flex; flex-wrap: wrap; gap: 40px; justify-content: space-between;
        }
        .left, .right {
            flex: 1; min-width: 300px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .left h2, .right h2 {
            font-size: 1.6rem;
            margin-bottom: 20px;
            color: #00695c;
            font-weight: 500;
        }
        .left p {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
        }
        .left i { color: #00796b; margin-right: 10px; }

        .form-group { margin-bottom: 20px; }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: border 0.3s ease;
        }
        input:focus, textarea:focus {
            border-color: #00796b;
            outline: none;
        }
        textarea { resize: vertical; height: 120px; }

        button {
            background-color: #00796b;
            color: white;
            padding: 12px 25px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #004d40;
        }

        .message {
            margin: 20px auto;
            max-width: 600px;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
        }
        .success { background-color: #4caf50; color: white; }
        .error { background-color: #f44336; color: white; }

        footer .footer-bottom {
            background-color: #00796b;
            color: white;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #004d40;
            padding: 15px 0;
            border-radius: 0 0 12px 12px;
        }

        @media screen and (max-width: 768px) {
            .contact-content { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="heading">
            <h1>Contact Us</h1>
        </div>

        <?php if ($msg): ?>
            <div class="message <?php echo strpos($msg, 'Thank') !== false ? 'success' : 'error'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="contact-content">
            <div class="left">
                <h2><i class="fas fa-map-marker-alt"></i>Reach Us</h2>
                <p>
                    You can submit your query here on our website, or you can directly visit the campus for more details.
                    <br><br>
                    <i class="fas fa-phone"></i> Tel: 9759790023<br>
                    <i class="fas fa-envelope"></i> Email: akhileshchaubey.230411222@gehu.ac.in<br>
                    <i class="fas fa-clock"></i> Open: 9 AM - 5 PM (Mon-Sat)
                </p>
            </div>
            <div class="right">
                <h2><i class="fas fa-paper-plane"></i> Submit Your Query</h2>
                <form method="POST" action="contact.php">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your GEHU Email" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> GEHU Event Management System | All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
