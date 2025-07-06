<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    echo json_encode(['error' => true, 'message' => 'Unauthorized access']);
    exit();
}

$user_id = $_SESSION['user_id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$address_house = $_POST['address_house'];
$address_street = $_POST['address_street'];
$address_city = $_POST['address_city'];
$address_province = $_POST['address_province'];

$query = "UPDATE customers_tbl SET firstname = ?, lastname = ?, phone = ?, address_house = ?, address_street = ?, address_city = ?, address_province = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssi", $firstname, $lastname, $phone, $address_house, $address_street, $address_city, $address_province, $user_id);
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
