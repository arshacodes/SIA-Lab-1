<!-- ?php
include '../db/db_conn.php';
session_start();
header('Content-Type: application/json');

$order_id  = intval($_POST['order_id']  ?? 0);
$status_id = intval($_POST['status_id'] ?? 0);

if (!$order_id || !$status_id) {
    echo json_encode(['error'=>true,'message'=>'Missing order ID or status']); exit;
}
if ($status_id > 2) {
    echo json_encode(['error'=>true,'message'=>'Admins may set status only to Pending or Processing']); exit;
}

$row = $conn->query("SELECT status_id FROM orders_tbl WHERE id=$order_id")->fetch_assoc();
if (!$row) { echo json_encode(['error'=>true,'message'=>'Order not found']); exit; }
$old_status = intval($row['status_id']);

if ($old_status != 2 && $status_id == 2) {
    $items = $conn->query(
        "SELECT product_id, quantity
           FROM order_products_tbl
          WHERE order_id = $order_id");
    while ($it = $items->fetch_assoc()) {
        $pid = $it['product_id'];
        $qty = $it['quantity'];

        $conn->query(
            "UPDATE products_tbl
               SET quantity_in_stock = quantity_in_stock - $qty
             WHERE id = $pid");

        $conn->query(
            "UPDATE products_tbl
               SET number_of_sales = number_of_sales + $qty
             WHERE id = $pid");
    }
}

$stmt = $conn->prepare("UPDATE orders_tbl SET status_id=? WHERE id=?");
$stmt->bind_param('ii', $status_id, $order_id);
$ok = $stmt->execute();

echo json_encode([
    'error'   => !$ok,
    'message' => $ok ? 'Order status updated.' : 'Failed to update order status'
]);

$stmt->close();
$conn->close(); -->

<?php
include 'db/db_conn.php';

$data = json_decode(file_get_contents("php://input"), true);

$order_id = intval($data['order_id'] ?? 0);
$order_status = intval($data['order_status'] ?? 0);

if ($order_id > 0 && $order_status > 0) {
    $stmt = $conn->prepare("UPDATE orders_tbl SET order_status = ? WHERE id = ?");
    $stmt->bind_param("ii", $order_status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'DB update failed']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
}

$conn->close();
?>
