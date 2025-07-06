<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    // header("Location: login.php");
    echo json_encode(['error' => true, 'message' => 'User not logged in']);
    exit();
}

$product_id = $_POST['product_id'] ?? null;
$customer_id = $_SESSION['user_id'];

// if ($_POST['avail_giftbox'] == 'yes'){
//     $avail_giftbox = 1;
// }else{
//     $avail_giftbox = 0;
// }
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if (!$product_id || $quantity < 1) {
    echo json_encode(['error' => true, 'message' => 'Invalid product or quantity']);
    exit();
}

$check_query = "SELECT quantity FROM cart_items_tbl WHERE product_id = ? AND customer_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("ii", $product_id, $customer_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_row = $check_result->fetch_assoc()) {
    $new_quantity = $check_row['quantity'] + $quantity;
    $update_query = "UPDATE cart_items_tbl SET quantity = ? WHERE product_id = ? AND customer_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iii", $new_quantity, $product_id, $customer_id);
    
    if ($update_stmt->execute()) {
        echo json_encode(['error' => false, 'message' => 'Updated cart item quantity!']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Failed to update quantity']);
    }

    $update_stmt->close();
} else {
    $insert_query = "INSERT INTO cart_items_tbl (product_id, customer_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iii", $product_id, $customer_id, $quantity);

    if ($insert_stmt->execute()) {
        echo json_encode(['error' => false, 'message' => 'Added to cart successfully!']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Failed to add to cart']);
    }

    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
// header("Location: ../cart.php")   // or wherever you’d like them to land
// exit();

?>
