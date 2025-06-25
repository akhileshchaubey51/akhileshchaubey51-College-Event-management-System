<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user details from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$profile_image = !empty($user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'uploads/profile.jpeg';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Event Management System - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Dropdown styling */
        .user-dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: white;
            
            margin-right: 20px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 140px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
            font-size: 15px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .user-dropdown:hover .dropdown-content {
            display: block;
        }

        .user-dropdown::after {
            content: " ";
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="page1">
        <div class="navbar">
            <div class="logo">
                <img src="login.png" alt="">
            </div>
            <ul class="nav-link">
                <li class="link"><a href="">Home</a></li>
                <li class="Events"><a href="events.php">Events</a>
                    <ul class="Event-menu">
                    <li><a href="tech-events.php">Tech Event</a></li>
                    <li><a href="Cultural.php">Cultural Event</a></li>
                    <li><a href="sport_events.php">Sports Event</a></li>
                </ul> </li>
                
                <li class="link"><a href="contact.php">Contact us</a></li>
                <li class="link"><a href="about.php">About</a></li>
            </ul>

            <!-- User dropdown -->
            <div class="user-dropdown d-flex align-items-center">
                <img src="<?php echo $profile_image; ?>" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                <div class="dropdown-content">
                    <a href="edit-profile.php">Edit Profile</a>
                    <a href="my_registrations.php">registered events</a>
                    <a href="change-password.php">Edit Password</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="page1-content">
        <p><span class="heading1">GEHU</span><br><span class="heading2">Event Management System</span> </p>
    </div>
    </div>

    <div id="page2">
        <p class="page2-head">Events</p>
        <div class="page2-content">
            <div class="page2-box" >
                 <div id="tech-box"></div>
                <p class="box-head">Tech Event</p>
                <p class="box-line">Explore to participate in the Events and win certificates and many more</p>
                <a href="tech-events.php">Visit</a>
            </div>
            <div class="page2-box" >
                <div id="cultural-box"></div>
                <p class="box-head">Cultural Event</p>
                <p class="box-line">Explore to participate in the Events and win certificates and many more</p>
                <a href="Cultural.php">Visit</a>
            </div>
            <div class="page2-box" >
                <div id="sports-box"></div>
                <p class="box-head">Sports Event</p>
                <p class="box-line">Explore to participate in the Events and win certificates and many more</p>
                <a href="sport_events.php">Visit</a>
            </div>
        </div>
    </div>
     <!-- Feedback Section (Form directly on Dashboard) -->
<div style="text-align: center; margin: 50px auto; padding: 30px; max-width: 600px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9;">
    <h2 style="margin-bottom: 20px;">Give Your Feedback</h2>
    <form action="submit-feedback.php" method="POST">
        <textarea name="feedback" rows="5" placeholder="Write your feedback here..." style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #ccc;" required></textarea>
        <br><br>
        <button type="submit" class="btn btn-success" style="background-color: green; color: white; font-size: 18px; padding: 10px 40px; border-radius: 10px; border: none;">
            Submit Feedback
        </button>
    </form>
</div>
    <footer style="background-color: #111; color: white; padding: 20px 0;">
    <div id="page3">
        <div class="page3-content">
            <img src="login.png" alt="">
        </div>
        <div class="page3-content">
            <p>Dehradun</p> <br>
            <span>Graphic Era Hill University</span><br>
            <span>Bell Road Clement Town Dehradun</span><br>
            <span>Uttarakhand</span>
        </div>
        <div class="page3-content">
            <p>Bhimtal</p> <br>
            <span>Graphic Era Hill University</span><br>
            <span>SatTal Road, Bhimtal, Nanital</span><br>
            <span>Uttarakhand</span>
        </div>
        <div class="page3-content">
            <p>Haldwani</p> <br>
            <span>Graphic Era Hill University</span><br>
            <span>Tularampur,Near Mahalaxmi Temple</span><br>
            <span>Uttarakhand</span>
        </div>
        
        
    </div>
    <!-- Footer Bottom -->
    <div class="footer-bottom" style=" color: white; text-align: center; margin-top: 30px; border-top: 1px solid #444; padding: 15px 0;">
        <p>&copy; <?php echo date('Y'); ?> GEHU Event Management System | All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>