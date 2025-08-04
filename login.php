<?php
session_start();
include("apps.php");

if(isset($_POST['submit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // You should hash this
    
    $sql = "SELECT * FROM login WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify password (if using password_hash())
        // if(password_verify($password, $user['password'])) {
        if($password === $user['password']) { // TEMPORARY - INSECURE!
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = true; // Add this column to your DB
            
            // Ensure no output before header()
            header("Location: admin.php");
            exit();
        }
    }
    
    // If login fails
    echo '<script>
        alert("Login failed. Invalid username or password");
        window.location.href = "index.php";
    </script>';
    exit();
}
?>