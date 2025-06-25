<?php
// index.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="styles.css">
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
            <button onclick="location.href='login.php';">Login</button>

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





