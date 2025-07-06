<?php
include '../db/db_conn.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$email = isset($_POST['customer_email']) ? $_POST['customer_email'] : '';
$password = isset($_POST['customer_password']) ? $_POST['customer_password'] : '';

$query = "SELECT * FROM customers_tbl WHERE email = ? AND account_status = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = "customer";

        $response = [
            'error' => false,
            'message' => 'Login successful',
        ];
        
        echo json_encode($response);
        exit();
    } else {
        echo json_encode(['error' => true, 'message' => 'Incorrect password']);
        exit();
    }
} else {
    echo json_encode(['error' => true, 'message' => 'Customer not found in database']);
    exit();
}

$stmt->close();
$conn->close();
?>
