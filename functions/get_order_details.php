<?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_POST['order_id'])) {
    echo json_encode(['error' => true, 'message' => 'Order ID missing']);
    exit();
}

$order_id = $_POST['order_id'];

$order_query = "
    SELECT o.id, o.reference_number, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, 
           IFNULL(o.price_total, 0) AS price_total, cs.name AS courier_name, o.address, 
           o.order_date, o.required_date, o.shipping_date, IFNULL(o.comments, 'No comments') AS comments, 
           os.name AS status_name
    FROM orders_tbl o
    LEFT JOIN customers_tbl c ON o.customer_id = c.id
    LEFT JOIN courier_services_tbl cs ON o.courier_service_id = cs.id
    LEFT JOIN order_status_tbl os ON o.status_id = os.id
    WHERE o.id = ?
";

$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order_details = $order_result->fetch_assoc();

if (!$order_details) {
    echo json_encode(['error' => true, 'message' => 'Order not found']);
    exit();
}

$product_query = "
    SELECT op.product_id, p.name, op.avail_engraving, op.avail_giftbox, op.quantity, 
           IFNULL(op.price_each, 0) AS price_each
    FROM order_products_tbl op
    JOIN products_tbl p ON op.product_id = p.id
    WHERE op.order_id = ?
";

$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

$products = [];
while ($product = $product_result->fetch_assoc()) {
    $products[] = $product;
}

echo json_encode([
    'error' => false,
    'order_details' => $order_details,
    'ordered_products' => $products
]);

$order_stmt->close();
$product_stmt->close();
$conn->close();
?>
