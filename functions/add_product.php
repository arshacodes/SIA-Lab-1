<?php
include '../db/db_conn.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$name = isset($_POST['add_product_name']) ? $_POST['add_product_name'] : '';
$description = isset($_POST['add_product_description']) ? $_POST['add_product_description'] : '';
$quantity_in_stock = isset($_POST['add_product_quantity_in_stock']) ? $_POST['add_product_quantity_in_stock'] : '';
$buy_price = isset($_POST['add_product_buy_price']) ? $_POST['add_product_buy_price'] : '';
$category_name = isset($_POST['add_product_category_name']) ? $_POST['add_product_category_name'] : '';

$query = "INSERT INTO products_tbl(name, description, quantity_in_stock, buy_price, category) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssids", $name, $description, $quantity_in_stock, $buy_price, $category_name);
$stmt->execute();

$response = [
    'error' => false,
    'message' => 'Process successful',
    'redirect' => 'admin_dashboard.php'
];


echo json_encode($response);
exit();

$stmt->close();
$conn->close();

?>