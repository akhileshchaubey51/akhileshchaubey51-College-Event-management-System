<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// Form submission logic
if (isset($_POST['submit'])) {
    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // Condition 1: Check if feedback is empty
    if (empty($feedback_text)) {
        echo "<script>alert('Feedback cannot be empty.');</script>";
    }
    // Condition 2: Minimum length check (10 characters)
    elseif (strlen($feedback_text) < 10) {
        echo "<script>alert('Feedback must be at least 10 characters long.');</script>";
    }
    // All conditions passed, insert feedback into database
    else {
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO feedback (user_id, comments, feedback_date) 
                  VALUES ('$user_id', '$feedback_text', NOW())";
        if (mysqli_query($conn, $query)) {
            // Display success message and redirect after 2 seconds
            echo "<script>
                    alert('Feedback Submitted!');
                    setTimeout(function() {
                        window.location.href = 'dashboard.php';
                    }, 2000); // Redirects to dashboard after 2 seconds
                  </script>";
            exit(); // Ensure script stops executing after the redirect
        } else {
            echo "<script>alert('Error submitting feedback.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <style>
        body {
            background: url('feedback_background.webp') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.6); /* Adjust transparency to 0.6 */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 40px 30px;
            margin-top: 80px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background-color: #28a745; /* Changed to Green */
            border-color: #28a745; /* Changed to Green */
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #218838; /* Darker Green on Hover */
            border-color: #218838; /* Darker Green on Hover */
        }

        .header-text {
            color: #28a745; /* Changed to Green */
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }


        .container {
            background-color: rgba(255, 255, 255, 0.6); /* Adjust transparency to 0.6 */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 40px 30px;
            margin-top: 80px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background-color: #28a745; /* Green */
            border-color: #28a745; /* Green */
            color: white; /* White text */
            padding: 15px 30px; /* Larger padding */
            border-radius: 8px; /* Slightly larger border radius */
            font-weight: bold;
            text-align: center;
            display: inline-block;
            text-decoration: none; /* Remove underline */
            cursor: pointer; /* Pointer cursor */
            font-size: 18px; /* Larger font size */
            width: 100%; /* Full width button */
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }

        .btn-primary:hover {
            background-color: #218838; /* Darker Green */
            border-color: #218838; /* Darker Green */
        }

        .header-text {
            color: #28a745; /* Green */
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .container input, .container textarea {
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            .header-text {
                font-size: 20px;
            }
        }

        .feedback-success-message {
            display: none;
            font-size: 18px;
            color: #28a745;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="header-text">Submit Your Feedback</h2>
        <form action="feedback.php" method="POST">
            <div class="form-group">
                <label for="feedback">Your Feedback</label>
                <textarea class="form-control" id="feedback" name="feedback" rows="5" required placeholder="Please enter your valuable feedback here..."></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mt-3">Submit Feedback</button>
        </form>
        
        <div class="feedback-success-message">
            Feedback submitted successfully! Redirecting to your dashboard...
        </div>
    </div>

    <script src="assets/bootstrap.bundle.min.js"></script>
</body>
</html>
