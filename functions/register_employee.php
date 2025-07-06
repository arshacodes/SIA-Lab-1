<?php
include '../db/db_conn.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$department = isset($_POST['new_employee_department']) ? $_POST['new_employee_department'] : '';
$firstname = isset($_POST['new_employee_firstname']) ? $_POST['new_employee_firstname'] : '';
$lastname = isset($_POST['new_employee_lastname']) ? $_POST['new_employee_lastname'] : '';
$email = isset($_POST['new_employee_email']) ? $_POST['new_employee_email'] : '';
$password = isset($_POST['new_employee_password']) ? $_POST['new_employee_password'] : '';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "SELECT * FROM employees_tbl WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $query2 = "INSERT INTO employees_tbl(department, email, password, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("issss", $department, $email, $hashed_password, $firstname, $lastname);
    $stmt2->execute();

    $response = [
        'error' => false,
        'message' => 'Registration successful',
        'redirect' => 'admin_dashboard.php'
    ];

    echo json_encode($response);
    exit();
} else {
    echo json_encode(['error' => true, 'message' => 'Employee already registered']);
    exit();
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
