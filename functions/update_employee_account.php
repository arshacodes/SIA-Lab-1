<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['error' => true, 'message' => 'Unauthorized access']);
    exit();
}

$user_id = $_SESSION['user_id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$query = "UPDATE employees_tbl SET firstname = ?, lastname = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $firstname, $lastname, $user_id);
$stmt->execute();

$response = [
    'error' => false,
    'message' => "Account updated successfully!"
];

echo json_encode($response);
exit();

$stmt->close();
$conn->close();
?>
