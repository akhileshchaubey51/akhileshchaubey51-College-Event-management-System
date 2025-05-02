<?php
include 'config.php';
$result = $conn->query("SELECT events.*, categories.name AS category_name FROM events LEFT JOIN categories ON events.category_id = categories.id");
?>

<table border="1">
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['category_name']; ?></td>
        <td><?php echo $row['event_date']; ?></td>
        <td><?php echo $row['event_time']; ?></td>
        <td><?php echo $row['location']; ?></td>
        <td><img src="uploads/event-images/<?php echo $row['image']; ?>" width="100"></td>
        <td>
            <a href="edit-event.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete-event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
