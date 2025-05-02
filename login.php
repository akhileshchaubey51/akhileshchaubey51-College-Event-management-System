<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cems";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error variable
$login_error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($email) && !empty($password)) {
        $email = mysqli_real_escape_string($conn, $email);

        // Fetch user from database
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ✅ Use password_verify() for hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                echo "<script>alert('🎉 Login successful!'); window.location.href='dashboard.php';</script>";
                exit;
            } else {
                $login_error = "🚫 Incorrect password!";
            }
        } else {
            $login_error = "🚫 User not found!";
        }
    } else {
        $login_error = "🚫 All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <style>
        /* Your existing styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://d.gehu.ac.in/uploads/image/Au46tJvh-campus-20-scaled.webp') no-repeat center center/cover;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
            color: #4CAF50;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            width: 340px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            color: #ffffff;
            text-align: center;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .login-container p {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-container a {
            color: #fff;
            text-decoration: underline;
        }

        .error {
            color: #ff8080;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="login.png" alt="College Logo"
            style="max-width: 250px; margin-bottom: 10px;">
        <h2>Login</h2>

        <?php
        if (!empty($login_error)) {
            echo "<div class='error'>$login_error</div>";
        }
        ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email (must end with @gehu.ac.in)" required />
            <input type="password" name="password" placeholder="🔑 Password" required />

            <label style="font-size: 14px; cursor: pointer;">
                <input type="checkbox" onclick="togglePassword()" /> 👁️ Show Password
            </label>

            <button type="submit">Login Now</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.querySelector('input[name="password"]');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
