<?php
session_start();
require_once 'apps.php';

// Debug: Check if admin is logged in
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    error_log("Admin access denied - redirecting to login");
    header("Location: index.php");
    exit();
}

// Debug: Log that admin dashboard was accessed
error_log("Admin dashboard accessed by ".$_SESSION['username']);
?>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel = "stylesheet" href = "admin.css">
</head>
<body>
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

    <h2>Member Registrations</h2>
    
    <?php
    // Debug: Log before query execution
    error_log("Attempting to fetch rewards data");
    
    $sql = "SELECT id, fullname, username, email, phone, registration_date FROM rewards ORDER BY registration_date DESC";
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Query failed: " . $conn->error);
        echo "<p>Error loading data. Please try again later.</p>";
    } else {
        if ($result->num_rows > 0) {
            echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registration Date</th>
                </tr>';
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".htmlspecialchars($row['id'])."</td>
                    <td>".htmlspecialchars($row['fullname'])."</td>
                    <td>".htmlspecialchars($row['username'])."</td>
                    <td>".htmlspecialchars($row['email'])."</td>
                    <td>".htmlspecialchars($row['phone'])."</td>
                    <td>".htmlspecialchars($row['registration_date'])."</td>
                </tr>";
            }
            echo '</table>';
        } else {
            error_log("No records found in rewards table");
            echo "<p>No registrations found.</p>";
        }
    }
    $conn->close();
    ?>
</body>
</html>