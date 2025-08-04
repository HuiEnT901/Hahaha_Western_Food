<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        session_regenerate_id(true);
        header("Location: admin.php");
        exit();
    } else {
        header("Location: login.html?error=1");
        exit();
    }
}
header("Location: login.html");
?>