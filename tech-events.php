<?php
include('includes/config.php');
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

try {
    // Fetch tech events
    $stmt = $dbh->prepare("SELECT * FROM tech_events WHERE event_type = 'Tech'");
    $stmt->execute();
    $techEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tech Events - EMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    #page1 {
        max-width: 1200px;
        margin: auto;
        padding: 40px 20px;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .filter_button {
        text-align: center;
        margin-bottom: 30px;
    }

    .buttons {
        background: white;
        color: black;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 30px;
        box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.25);
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    .buttons:hover {
        background: #138496;
    }

    .buttons.active {
        background: #138496;
        color: white;
        border: none;
    }

    .element-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        justify-content: center;
    }

    .filterelement {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.3s;
    }

    .filterelement:hover {
        transform: translateY(-5px);
    }

    .box-image img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .filterelement p {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin: 10px 0 5px;
    }

    .filterelement span {
        font-size: 14px;
        color: #666;
    }

    .filterelement form {
        margin-top: 10px;
    }

    .filterelement button {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s;
    }

    .filterelement button:hover {
        background: #138496;
    }

    .hide {
        display: none;
    }

    .show {
        display: block;
    }
  </style>
</head>

<body>
  <div id="page1">
    <h1>Tech Events</h1>
    <div class="filter_button">
        <button class="buttons active" data-filter="all">All</button>
        <button class="buttons" data-filter="BGMI">BGMI</button>
        <button class="buttons" data-filter="Free-fire">Free fire</button>
        <button class="buttons" data-filter="Takken">Takken</button>
        <button class="buttons" data-filter="Hackathon">Hackathon</button>
        <button class="buttons" data-filter="Coding">Coding</button>
    </div>

    <div class="element-container">
        <?php foreach ($techEvents as $event): ?>
            <div class="filterelement <?php echo htmlspecialchars(str_replace(' ', '-', $event['category'])); ?>">
                <div class="box-image">
                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" />
                </div>
                <p><?php echo htmlspecialchars($event['event_name']); ?></p>
                <span><?php echo nl2br(htmlspecialchars($event['title'])); ?></span><br>
                <button onclick="window.location.href='view_tech.php?event_name=<?php echo urlencode($event['event_name']); ?>'">View Details</button>
                <?php if ($isLoggedIn): ?>
                    <button onclick="window.location.href='register_event.php?event_name=<?php echo urlencode($event['event_name']); ?>&event_type=Tech'">Register</button>
                <?php else: ?>
                    <button onclick="window.location.href='login.php'">Register</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
  </div>

  <script>
    const filterButtons = document.querySelectorAll(".buttons");
    const elements = document.querySelectorAll(".filterelement");

    filterButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            document.querySelector(".buttons.active").classList.remove("active");
            btn.classList.add("active");

            const filter = btn.getAttribute("data-filter");

            elements.forEach(el => {
                if (filter === "all" || el.classList.contains(filter)) {
                    el.classList.remove("hide");
                    el.classList.add("show");
                } else {
                    el.classList.remove("show");
                    el.classList.add("hide");
                }
            });
        });
    });
  </script>
</body>
</html>
