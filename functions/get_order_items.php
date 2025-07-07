<?php
include 'db/db_conn.php';
$orderId = intval($_GET['id'] ?? 0);

$sql = "
SELECT p.name, oi.unit_price, oi.quantity
FROM order_items_tbl oi
JOIN products_tbl p ON oi.product_id = p.id
WHERE oi.order_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
  $total = $item['unit_price'] * $item['quantity'];
  echo "<tr>
          <td>" . htmlspecialchars($item['name']) . "</td>
          <td>₱" . number_format($total, 2) . "</td>
          <td>" . $item['quantity'] . "</td>
        </tr>";
}

$stmt->close();
$conn->close();
?>
