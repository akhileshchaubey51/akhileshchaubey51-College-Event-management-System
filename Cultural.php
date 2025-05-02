<?php
include('includes/config.php');
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

try {
    // Fetch cultural events
    $stmt = $dbh->prepare("SELECT * FROM cultural_events WHERE event_type = 'Cultural'");
    $stmt->execute();
    $culturalEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cultural Events</title>
    <style>
        /* Your styling */
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
        <h1>Cultural Events</h1>

        <div class="filter_button">
            <button class="buttons active" data-filter="all">All</button>
            <button class="buttons" data-filter="Holi">Holi</button>
            <button class="buttons" data-filter="Diwali">Diwali</button>
            <button class="buttons" data-filter="Freshers">Freshers</button>
        </div>

        <div class="element-container">
            <?php foreach ($culturalEvents as $event): ?>
                <?php
                    // Handle empty category
                    $categoryClass = isset($event['category']) && !empty($event['category']) ? str_replace(' ', '-', htmlspecialchars($event['category'])) : 'uncategorized';
                ?>
                <div class="filterelement all <?php echo $categoryClass; ?>">
                    <div class="box-image">
                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                    </div>
                    <p><?php echo htmlspecialchars($event['event_name']); ?></p>
                    <span>On <?php echo htmlspecialchars(date('d-M', strtotime($event['event_date']))); ?></span><br>
                    <?php if ($isLoggedIn): ?>
                        <form method="POST" action="register_event.php?event_id=<?php echo $event['id']; ?>">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit">Register</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php"><button> Register</button></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <script>
        const filterbuttons = document.querySelectorAll(".buttons");
        const elements = document.querySelectorAll(".filterelement");

        filterbuttons.forEach(button => {
            button.addEventListener("click", () => {
                document.querySelector(".buttons.active").classList.remove("active");
                button.classList.add("active");
                const filter = button.getAttribute("data-filter");

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