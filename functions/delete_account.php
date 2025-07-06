<?php
include '../db/db_conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => true, 'message' => 'User not logged in']);
    exit();
}

$customer_id = $_SESSION['user_id'];

$query = "UPDATE customers_tbl SET firstname = 'Deleted', lastname = 'Deleted', phone = 'Deleted', email = 'Deleted', address_house = 'Deleted', address_street = 'Deleted', address_city = 'Deleted', address_province = 'Deleted', account_status = 2, password = 'Deleted' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);

if ($stmt->execute()) {
    session_destroy();
    echo json_encode(['error' => false]);
} else {
    echo json_encode(['error' => true]);
}

$stmt->close();
$conn->close();
?>
