<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
file_put_contents('debug.txt', "Reached data.php\n", FILE_APPEND);
require_once 'connections.php';

// Debug: Log that the script was reached
error_log("data.php accessed");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Log received data
    error_log("POST data: " . print_r($_POST, true));
    
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Debug: Log escaped values
    error_log("Escaped values - Fullname: $fullname, Username: $username, Email: $email, Phone: $phone");

    $sql = "INSERT INTO rewards (fullname, username, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $username, $email, $phone);
    
    if($stmt->execute()) {
        error_log("Insert successful");
        echo "<script>alert('Registration successful!'); window.location.href = 'rewards.html';</script>";
    } else {
        error_log("Insert failed: " . $stmt->error);
        echo "<script>alert('Registration failed: ".addslashes($stmt->error)."'); history.back();</script>";
    }
    
    $stmt->close();
} else {
    error_log("Invalid request method");
}
$conn->close();
?>