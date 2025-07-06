<?php
include '../db/db_conn.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$email = isset($_POST['admin_email']) ? $_POST['admin_email'] : '';
$password = isset($_POST['admin_password']) ? $_POST['admin_password'] : '';

$query = "SELECT * FROM employees_tbl WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = "admin";

        $query2 = "SELECT * FROM departments_tbl WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $user['department']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $_SESSION['user_department'] = $result2->fetch_assoc()['name'];
        }

        $response = [
            'error' => false,
            'message' => 'Login successful',
            'redirect' => ''
        ];

        if ($_SESSION['user_department'] == "Sales"){
            $response['redirect'] = "sales_dashboard.php";
        }

        if ($_SESSION['user_department'] == "Inventory"){
            $response['redirect'] = "inventory_dashboard.php";
        }

        if ($_SESSION['user_department'] == "Admin"){
            $response['redirect'] = "admin_dashboard.php";
        }

        echo json_encode($response);
        exit();
    } else {
        echo json_encode(['error' => true, 'message' => 'Incorrect password']);
        exit();
    }
} else {
    echo json_encode(['error' => true, 'message' => 'User not found in database']);
    exit();
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
