<?php
include '../db/db_conn.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['error'=>true,'message'=>'Login required.']); exit; }

$customer_id = $_SESSION['user_id'];
$order_id    = $_POST['order_id'] ?? 0;

$chk = $conn->prepare("SELECT status_id FROM orders_tbl WHERE id=? AND customer_id=?");
$chk->bind_param("ii",$order_id,$customer_id);
$chk->execute();
$chkRes = $chk->get_result()->fetch_assoc();
if(!$chkRes){ echo json_encode(['error'=>true,'message'=>'Order not found.']); exit; }
if($chkRes['status_id'] != 2){ echo json_encode(['error'=>true,'message'=>'Order is not in To-Receive state.']); exit; }

$upd=$conn->prepare("UPDATE orders_tbl SET status_id=3, shipping_date = NOW() WHERE id=?");
$upd->bind_param("i",$order_id);
$upd->execute();

echo json_encode(['error'=>false,'message'=>'Order marked as received!']);
