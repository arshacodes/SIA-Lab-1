<?php
include '../db/db_conn.php';

$id = $_POST['id'];
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$quantity_in_stock = $_POST['quantity_in_stock'] ?? '';
$buy_price = $_POST['buy_price'] ?? '';
$category_name = $_POST['category_name'] ?? '';

$query = "UPDATE products_tbl SET ";
$params = [];
$types = "";

if (!empty($name)) {
    $query .= "name = ?, ";
    $params[] = $name;
    $types .= "s";
}
if (!empty($description)) {
    $query .= "description = ?, ";
    $params[] = $description;
    $types .= "s";
}
if (!empty($quantity_in_stock)) {
    $query .= "quantity_in_stock = ?, ";
    $params[] = $quantity_in_stock;
    $types .= "i";
}
if (!empty($buy_price)) {
    $query .= "buy_price = ?, ";
    $params[] = $buy_price;
    $types .= "d";
}
if (!empty($category_name)) {
    $query .= "category = ?";
    $params[] = $category_name;
    $types .= "i";
}

$query = preg_replace('/,\s*$/', '', $query) . " WHERE id=?";
$params[] = $id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();

echo "Product updated successfully!";
$conn->close();
?>
