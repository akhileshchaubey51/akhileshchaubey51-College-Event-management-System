<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

$query = "SELECT * FROM event_registrations ORDER BY registered_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Event Registrations</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      padding: 30px;
      background-color: #f2f2f2;
    }
    .container {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    table th, table td {
      text-align: center;
      vertical-align: middle;
    }
    img.thumbnail {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .badge-success {
      background-color: #28a745;
    }
    .badge-danger {
      background-color: #dc3545;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>All Event Registrations</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
  <table class="table table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Event Name</th>
        <th>Event Type</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Amount (₹)</th>
        <th>Payment Status</th>
        <th>Screenshot</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['event_name']) ?></td>
          <td><?= htmlspecialchars($row['event_type']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['phone']) ?></td>
          <td><?= number_format($row['amount'], 2) ?></td>
          <td>
            <?php if (strtolower($row['payment_status']) === 'paid'): ?>
              <span class="badge badge-success">Paid</span>
            <?php else: ?>
              <span class="badge badge-danger">Unpaid</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if (!empty($row['payment_screenshot'])): ?>
              <a href="<?= htmlspecialchars($row['payment_screenshot']) ?>" target="_blank">
                <img src="<?= htmlspecialchars($row['payment_screenshot']) ?>" alt="screenshot" class="thumbnail">
              </a>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </td>
          <td><?= date('d M Y, h:i A', strtotime($row['registered_at'])) ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="text-center">No registrations found.</p>
  <?php endif; ?>
</div>

</body>
</html>
