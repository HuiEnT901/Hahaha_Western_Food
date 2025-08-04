<?php
// view_orders.php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "foodiefav"; // ✅ Replace with your actual DB name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders ORDER BY order_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Order Viewer</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #f4f4f4;
    }

    /* ===== Nav Bar ===== */
    .navbar {
      background-color: #232323;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      margin-left: 20px;
    }

    .navbar a:hover {
      color: #f15b2a;
    }

    h1 {
      text-align: center;
      margin-top: 20px;
      color: #333;
    }

    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #f15b2a;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .no-data {
      text-align: center;
      padding: 50px;
      font-size: 18px;
      color: #666;
    }
  </style>
</head>
<body>

  <!-- ===== Nav Bar ===== -->
<div class="navbar">
  <div>
    <a href="index.html">🏠 Home</a>
    <a href="admin.php">🧑‍💼 Members Registration</a>
    <a href="view_orders.php">📃 Orders </a>
  </div>
  <div>
    <a href="index.php">🔓 Logout</a>
  </div>
</div>

  <h1>🧾 Customer Orders</h1>

  <?php if ($result && $result->num_rows > 0): ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Table #</th>
      <th>Item</th>
      <th>Qty</th>
      <th>Price (RM)</th>
      <th>Total (RM)</th>
      <th>Ordered At</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row["id"] ?></td>
      <td><?= $row["table_number"] ?></td>
      <td><?= htmlspecialchars($row["item_name"]) ?></td>
      <td><?= $row["quantity"] ?></td>
      <td><?= number_format($row["price"], 2) ?></td>
      <td><?= number_format($row["price"] * $row["quantity"], 2) ?></td>
      <td><?= $row["order_time"] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <div class="no-data">No orders found.</div>
  <?php endif; ?>

  <?php $conn->close(); ?>

</body>
</html>
