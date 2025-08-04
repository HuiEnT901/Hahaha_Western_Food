<?php
// admin_order.php

header("Content-Type: application/json");

// === 1. Database config ===
$host = "localhost";
$user = "root";
$password = "";  // Change if your MySQL has a password
$dbname = "foodiefav"; // ✅ Replace with your actual database name

$conn = new mysqli($host, $user, $password, $dbname);

// === 2. Check connection ===
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// === 3. Decode JSON input ===
$data = json_decode(file_get_contents("php://input"), true);

// === 4. Validate data ===
if (!isset($data['table']) || !isset($data['items']) || !is_array($data['items'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
    exit();
}

$tableNumber = intval($data['table']);
$items = $data['items'];

// === 5. Prepare SQL insert ===
$stmt = $conn->prepare("INSERT INTO orders (table_number, item_name, quantity, price) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "SQL prepare failed"]);
    exit();
}

// === 6. Execute insert for each item ===
foreach ($items as $item) {
    $name = $item['name'];
    $quantity = intval($item['quantity']);
    $price = floatval($item['price']);

    $stmt->bind_param("isid", $tableNumber, $name, $quantity, $price);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "message" => "Order submitted"]);
