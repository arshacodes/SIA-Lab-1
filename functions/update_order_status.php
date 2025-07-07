<?php
include '../db/db_conn.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

$orderId = intval($data['order_id'] ?? 0);
$orderStatus = intval($data['order_status'] ?? 0);
$shipping_date = date('Y-m-d H:i:s');

if ($orderId > 0 && $orderStatus > 0) {
    if($orderStatus == 2){
        $stmt = $conn->prepare("UPDATE orders_tbl SET order_status = ?, shipping_date = ? WHERE id = ?");
        $stmt->bind_param("isi", $orderStatus, $shipping_date, $orderId);
    }else{
        $stmt = $conn->prepare("UPDATE orders_tbl SET order_status = ?, shipping_date = null  WHERE id = ?");
        $stmt->bind_param("ii", $orderStatus, $orderId);
    }

  if ($stmt->execute()) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => "Failed to execute"]);
  }

  $stmt->close();
} else {
  echo json_encode(["success" => false, "error" => "Invalid input"]);
}

$conn->close();
?>

