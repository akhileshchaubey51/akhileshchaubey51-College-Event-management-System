<?php
include('includes/config.php');
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

try {
    // Fetch sport events
    $stmt = $dbh->prepare("SELECT * FROM sport_events WHERE event_type = 'Sports'");
    $stmt->execute();
    $sportEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Sport Events - EMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
  <style>
    body {
        font-family: 'Times New Roman', serif;
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
        box-shadow: 0px 5px 15px rgba(0,0,0,0.25);
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    .buttons:hover,
    .buttons.active {
        background: #138496;
        color: white;
    }

    .element-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .filterelement {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        text-align: center;
        padding: 15px;
        transition: transform 0.3s;
    }

    .filterelement:hover {
        transform: translateY(-5px);
    }

    .filterelement img {
        height: 180px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .filterelement p {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 10px 0 5px;
    }

    .filterelement span {
        font-size: 14px;
        color: #777;
    }

    .filterelement .btn-group {
        margin-top: 12px;
    }

    .filterelement button {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        margin: 5px;
        transition: background 0.3s;
    }

    .filterelement button:hover {
        background: #138496;
    }

    .hide {
        display: none;
    }
  </style>
</head>

<body>
  <div id="page1">
    <h1>Sport Events</h1>
    <div class="filter_button">
        <button class="buttons active" data-filter="all">All</button>
        <button class="buttons" data-filter="Cricket">Cricket</button>
        <button class="buttons" data-filter="Basketball">Basketball</button>
        <button class="buttons" data-filter="Volleyball">Volleyball</button>
        <button class="buttons" data-filter="Tressure Hunt">Tressure Hunt</button>
        <button class="buttons" data-filter="Badminton">Badminton</button>
        <button class="buttons" data-filter="Riddle">Riddle</button>
    </div>

    <div class="element-container">
        <?php foreach ($sportEvents as $event): 
            $categorySlug = strtolower(str_replace(' ', '-', $event['event_name']));
        ?>
            <div class="filterelement <?php echo htmlspecialchars($categorySlug); ?>">
                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" />
                <p><?php echo htmlspecialchars($event['event_name']); ?></p>
                <span><?php echo nl2br(htmlspecialchars($event['title'])); ?></span><br>
                <button onclick="window.location.href='view_sport.php?event_name=<?php echo urlencode($event['event_name']); ?>'">View Details</button>
                <?php if ($isLoggedIn): ?>
                    <button onclick="window.location.href='register_event.php?event_name=<?php echo urlencode($event['event_name']); ?>&event_type=Sport'">Register</button>
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

            const rawFilter = btn.getAttribute("data-filter");
            const filter = rawFilter.toLowerCase().replace(/\s+/g, '-');

            elements.forEach(el => {
                if (filter === "all" || el.classList.contains(filter)) {
                    el.classList.remove("hide");
                } else {
                    el.classList.add("hide");
                }
            });
        });
    });
  </script>
</body>
</html>
