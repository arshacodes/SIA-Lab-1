<?php
include '../db/db_conn.php';

$id = $_POST['id'];

$query = "DELETE FROM products_tbl WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Product deleted successfully!";
$conn->close();
?>
