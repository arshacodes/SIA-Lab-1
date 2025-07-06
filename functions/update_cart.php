<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => true, 'message' => 'User not logged in']);
    exit();
}

$cart_item_id = $_POST['cart_item_id'];
$new_quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;

$query = "SELECT product_id, quantity FROM cart_items_tbl WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cart_item_id);
$stmt->execute();
$result = $stmt->get_result();
$current_item = $result->fetch_assoc();
$stmt->close();

if (!$current_item) {
    echo json_encode(['error' => true, 'message' => 'Cart item not found']);
    exit();
}

$new_quantity = $new_quantity ?? $current_item['quantity'];

$update_query = "UPDATE cart_items_tbl SET quantity = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("ii", $new_quantity, $cart_item_id);

if ($update_stmt->execute()) {
    echo json_encode(['error' => false, 'message' => 'Cart updated successfully!']);
} else {
    echo json_encode(['error' => true, 'message' => 'Failed to update cart']);
}

$update_stmt->close();

$conn->close();
?>
