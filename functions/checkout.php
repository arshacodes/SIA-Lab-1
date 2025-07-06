<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => true, 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$customer_id     = $_SESSION['user_id'];
$courier_service = intval($data['courier_service']);
$comments        = $data['comments'] ?? '';
$selected_items  = array_filter($data['selected_items'] ?? [], 'is_numeric');
$total_amount    = floatval($data['total_amount'] ?? 0);

if (empty($selected_items) || $total_amount <= 0) {
    echo json_encode(['error' => true, 'message' => 'Invalid checkout data']);
    exit();
}

$query = "SELECT address_house, address_street, address_city, address_province FROM customers_tbl WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$address_house    = $row['address_house'];
$address_street   = $row['address_street'];
$address_city     = $row['address_city'];
$address_province = $row['address_province'];

function generate_unique_reference($conn) {
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $length = 10;
    
    do {
        $reference_code = "";
        for ($i = 0; $i < $length; ++$i) {
            $reference_code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $query = "SELECT id FROM orders_tbl WHERE reference_code = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $reference_code);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    $stmt->close();
    return $reference_code;
}

$reference_code = generate_unique_reference($conn);
$required_date    = date('Y-m-d H:i:s', strtotime('+7 days'));

$query = "
    INSERT INTO orders_tbl (
    reference_code, customer_id, total_amount, courier_service,
    address_house, address_street, address_city, address_province,
    required_date, comments
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "siiissssss",
    $reference_code,
    $customer_id,
    $total_amount,
    $courier_service,
    $address_house,
    $address_street,
    $address_city,
    $address_province,
    $required_date,
    $comments
);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;

    $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
    $item_query = "
        INSERT INTO order_items_tbl (order_id, product_id, quantity, unit_price)
        SELECT ?, ci.product_id, ci.quantity, p.shelf_price
        FROM cart_items_tbl ci
        JOIN products_tbl p ON ci.product_id = p.id
        WHERE ci.id IN ($placeholders)
    ";
    
    $item_stmt = $conn->prepare($item_query);
    $types = str_repeat('i', count($selected_items) + 1);
    $item_stmt->bind_param($types, $order_id, ...$selected_items);

    if ($item_stmt->execute()) {
        $delete_query = "DELETE FROM cart_items_tbl WHERE id IN ($placeholders)";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);

        if ($delete_stmt->execute()) {
            echo json_encode(['error' => false, 'message' => 'Checkout successful!']);
        } else {
            echo json_encode(['error' => true, 'message' => 'Failed to remove items from cart']);
        }

        $delete_stmt->close();
    } else {
        echo json_encode(['error' => true, 'message' => 'Failed to insert order items']);
    }

    $item_stmt->close();
} else {
    echo json_encode(['error' => true, 'message' => 'Failed to process checkout']);
}

$stmt->close();
$conn->close();
?>
