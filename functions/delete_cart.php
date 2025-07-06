<?php
include '../db/db_conn.php';

$cart_item_id = $_POST['cart_item_id'];

$query = "DELETE FROM cart_items_tbl WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cart_item_id);
$stmt->execute();

echo json_encode(['error' => false, 'message' => 'Cart item deleted successfully!']);

$stmt->close();
$conn->close();
?>
