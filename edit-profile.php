<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    // Validate & upload image if provided
    if (!empty($_FILES['profile_image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $imageType = $_FILES['profile_image']['type'];
        $imageSize = $_FILES['profile_image']['size'];
        $imageTmp = $_FILES['profile_image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['profile_image']['name']);

        if (!in_array($imageType, $allowedTypes)) {
            $error = "Only JPG and PNG images are allowed.";
        } elseif ($imageSize > $maxSize) {
            $error = "Image size should not exceed 2MB.";
        } else {
            move_uploaded_file($imageTmp, 'uploads/' . $imageName);
            $update = "UPDATE users SET name = '$name', profile_image = '$imageName' WHERE id = $user_id";
        }
    } else {
        $update = "UPDATE users SET name = '$name' WHERE id = $user_id";
    }

    if ($conn->query($update)) {
        // Redirect to dashboard after successful update
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error updating profile. Please try again.";
    }
    
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <style>
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
            background: url('profile_background.webp') no-repeat center center/cover;
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

        input[type="text"], input[type="file"] {
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
        .image-preview img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

        .success {
        background: rgba(40,167,69,0.15);
        color: #28a745;
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
        <img src="login.png" alt="College Logo" style="max-width: 250px; margin-bottom: 10px;">
        <h2>Edit Profile</h2>

    <form method="POST" enctype="multipart/form-data">
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Profile Image:</label>
        <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)">

        <div class="image-preview">
            <img id="preview" src="<?php echo !empty($user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'uploads/default.jpg'; ?>" alt="Profile Image">
        </div>

        <button type="submit">Update Profile</button>
    </form>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                document.getElementById('preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
