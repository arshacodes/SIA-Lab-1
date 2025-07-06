<!-- ?php
include '../db/db_conn.php';
session_start();

header('Content-Type: application/json');

if (!isset($_POST['order_id'])) {
    echo json_encode(['error' => true, 'message' => 'Order ID missing']);
    exit();
}

$order_id = $_POST['order_id'];

$order_query = "
    SELECT o.id, o.reference_code, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, 
           IFNULL(o.total_amount, 0) AS total_amount, cs.name AS courier_name, o.address_house, o.address_street, o.address_city, o.address_province 
           o.checkout_date, o.required_date, o.shipping_date, IFNULL(o.comments, 'No comments') AS comments, 
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
?> -->

<?php
include 'db/db_conn.php';

$order_id = intval($_GET['id'] ?? 0);

$query = "
SELECT 
    o.reference_code,
    o.customer_id,
    CONCAT(c.firstname, ' ', c.lastname) AS customer_name,
    o.total_amount,
    cs.name AS courier,
    CONCAT(o.address_house, ', ', o.address_street, ', ', o.address_city, ', ', o.address_province) AS address,
    DATE(o.order_date) AS order_date,
    DATE(o.required_date) AS required_date,
    DATE(o.shipping_date) AS shipping_date,
    o.comments
FROM orders_tbl o
JOIN customers_tbl c ON o.customer_id = c.id
JOIN courier_services_tbl cs ON o.courier_service = cs.id
WHERE o.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$items = [];
$item_query = "
SELECT 
    p.name AS product_name,
    oi.unit_price,
    oi.quantity,
    oi.engraving,
    oi.giftbox
FROM order_items_tbl oi
JOIN products_tbl p ON oi.product_id = p.id
WHERE oi.order_id = ?
";
$item_stmt = $conn->prepare($item_query);
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();
while ($item_row = $item_result->fetch_assoc()) {
    $items[] = $item_row;
}
$item_stmt->close();

echo json_encode(["order" => $order, "items" => $items]);
$conn->close();
?>

