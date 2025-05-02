<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = $_POST['location'];
    $category_id = $_POST['category_id'];

    // Image upload
    $image = $_FILES['image']['name'];
    $target = "uploads/event-images/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $sql = "INSERT INTO events (title, description, event_date, event_time, location, category_id, image)
            VALUES ('$title', '$description', '$event_date', '$event_time', '$location', '$category_id', '$image')";

    if ($conn->query($sql) === TRUE) {
        echo "Event added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Event Title" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <input type="date" name="event_date" required><br>
    <input type="time" name="event_time" required><br>
    <input type="text" name="location" placeholder="Location" required><br>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        $catResult = $conn->query("SELECT * FROM categories");
        while ($cat = $catResult->fetch_assoc()) {
            echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
        }
        ?>
    </select><br>
    <input type="file" name="image"><br>
    <button type="submit">Add Event</button>
</form>
