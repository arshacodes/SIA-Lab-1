<?php
include '../db/db_conn.php';

$id = $_POST['id'];

$query = "DELETE FROM employees_tbl WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Employee deleted successfully!";
$conn->close();
?>
