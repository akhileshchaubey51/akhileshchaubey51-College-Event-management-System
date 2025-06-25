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
    if (!empty($_FILES['profile_image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $imageType = $_FILES['profile_image']['type'];
        $imageSize = $_FILES['profile_image']['size'];
        $imageTmp = $_FILES['profile_image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['profile_image']['name']);

        if (!in_array($imageType, $allowedTypes)) {
            $error = "Only JPG, PNG, or WEBP images are allowed.";
        } elseif ($imageSize > $maxSize) {
            $error = "Image size should not exceed 2MB.";
        } else {
            move_uploaded_file($imageTmp, 'uploads/' . $imageName);
            $update = "UPDATE users SET profile_image = '$imageName' WHERE id = $user_id";
            
            if (empty($error)) {
                if ($conn->query($update)) {
                    $_SESSION['user_name'] = $user['name']; // Preserve existing name
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Error updating profile image. Please try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            margin-bottom: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            width: 360px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            color: #000000;
            text-align: center;
        }

        input[type="file"],
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            background-color: #fff;
            color: #000;
        }

        input[disabled] {
            background-color: #f0f0f0;
            color: #666;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
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

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .success {
            background: rgba(40,167,69,0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }

        .error {
            background: rgba(255,0,0,0.1);
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
        }

        .image-preview {
            margin-top: 10px;
        }

        .image-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4CAF50;
            transition: all 0.3s ease-in-out;
        }

        @media screen and (max-width: 400px) {
            .login-container {
                width: 90%;
                padding: 30px 20px;
            }
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
        <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>

        <label>Profile Image:</label>
        <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)">

        <div class="image-preview">
            <img id="preview" src="<?php echo !empty($user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'uploads/default.jpg'; ?>" alt="Profile Image">
        </div>

        <button type="submit">Update Profile Image</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>
