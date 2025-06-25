<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - CEMS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eee;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #343a40;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 17px;
            line-height: 1.8;
            color: #555;
        }

        .footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

       img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    display: block;
    border-radius: 10px;
}

    </style>
</head>
<body>

<div class="header">
    <h1>About Us</h1>
</div>

<div class="container">
    <h2>Welcome to College Event Management System - Gehu Haldwani</h2>
    <p>
        The College Event Management System (CEMS) is a digital platform developed to streamline event handling,
        registration, and coordination for Graphic Era Hill University, Haldwani Campus. Our goal is to simplify the process
        of organizing cultural, technical, and sports events, while providing students with a user-friendly experience.
    </p> <img src="aboutus.webp" alt="GEHU Haldwani Campus">

    <p>
        At <strong>GEHU Haldwani</strong>, we believe in fostering creativity, innovation, and collaboration among students.
        Through this portal, we aim to digitize the event experience—from discovering new events to easy online registrations
        and real-time updates.
    </p>

    <p>
        This project was built with technologies such as PHP, MySQL, HTML, CSS, and JavaScript. It supports user registration, event filtering,
        booking with payment (if applicable), admin controls, and data analytics.
    </p>

    <p>
        We hope this initiative enhances campus engagement and offers a smoother experience for both organizers and participants.
    </p>
</div>

<div class="footer">
    &copy; <?= date("Y") ?> Graphic Era Hill University, Haldwani | CEMS Project
</div>

</body>
</html>
