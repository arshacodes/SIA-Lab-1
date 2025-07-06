<?php
include '../db/db_conn.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$firstname = isset($_POST['new_customer_firstname']) ? $_POST['new_customer_firstname'] : '';
$lastname = isset($_POST['new_customer_lastname']) ? $_POST['new_customer_lastname'] : '';
$phone = isset($_POST['new_customer_phone']) ? $_POST['new_customer_phone'] : '';
$email = isset($_POST['new_customer_email']) ? $_POST['new_customer_email'] : '';
$house = isset($_POST['new_customer_house']) ? $_POST['new_customer_house'] : '';
$street = isset($_POST['new_customer_street']) ? $_POST['new_customer_street'] : '';
$city = isset($_POST['new_customer_city']) ? $_POST['new_customer_city'] : '';
$province = isset($_POST['new_customer_province']) ? $_POST['new_customer_province'] : '';
$password = isset($_POST['new_customer_password']) ? $_POST['new_customer_password'] : '';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "SELECT * FROM customers_tbl WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $query2 = "INSERT INTO customers_tbl(firstname, lastname, phone, email, address_house, address_street, address_city, address_province, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("sssssssss", $firstname, $lastname, $phone, $email, $house, $street, $city, $province, $hashed_password);
    $stmt2->execute();

    $response = [
        'error' => false,
        'message' => 'Sign up successful',
    ];

    echo json_encode($response);
    exit();
} else {
    echo json_encode(['error' => true, 'message' => 'Account already exists']);
    exit();
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
