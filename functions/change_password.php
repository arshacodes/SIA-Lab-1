<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    echo json_encode(['error' => true, 'message' => 'Unauthorized access']);
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

$query = "SELECT password FROM customers_tbl WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!password_verify($current_password, $row['password'])) {
    echo json_encode(['error' => true, 'message' => 'Incorrect current password']);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(['error' => true, 'message' => 'New passwords do not match']);
    exit();
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$query = "UPDATE customers_tbl SET password = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $hashed_password, $user_id);
$stmt->execute();
$stmt->close();

echo json_encode(['error' => false, 'message' => 'Password updated successfully']);
exit();
?>
