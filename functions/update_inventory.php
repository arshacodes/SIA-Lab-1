<?php
include '../db/db_conn.php';

$id = $_POST['id'];
$quantity_in_stock = $_POST['quantity_in_stock'] ?? '';

$query = "UPDATE products_tbl SET ";
$params = [];
$types = "";

if (!empty($quantity_in_stock)) {
    $query .= "quantity_in_stock = ?, ";
    $params[] = $quantity_in_stock;
    $types .= "i";
}

$query = preg_replace('/,\s*$/', '', $query) . " WHERE id=?";
$params[] = $id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();

echo "Inventory updated successfully!";
$conn->close();
?>
