<?php
include '../db/db_conn.php';

$id = $_POST['id'];
$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$department = $_POST['department'] ?? ''; 

$query = "UPDATE employees_tbl SET ";
$params = [];
$types = "";

if (!empty($firstname)) {
    $query .= "firstname = ?, ";
    $params[] = $firstname;
    $types .= "s";
}
if (!empty($lastname)) {
    $query .= "lastname = ?, ";
    $params[] = $lastname;
    $types .= "s";
}
if (!empty($department)) {
    $query .= "department = ?";
    $params[] = $department;
    $types .= "i";
}

$query = rtrim($query, ", ") . " WHERE id=?";
$params[] = $id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();

echo "Employee updated successfully!";
$conn->close();
?>
