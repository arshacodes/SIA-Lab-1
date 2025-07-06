<?php
include '../db/db_conn.php';
session_start();
header('Content-Type: application/json');

$order_id    = $_POST['order_id'] ?? 0;
$customer_id = $_SESSION['user_id'] ?? 0;

$sql = "
 SELECT p.id AS product_id, p.name
 FROM order_products_tbl op
 JOIN products_tbl p ON op.product_id = p.id
 WHERE op.order_id = ?
   AND NOT EXISTS (SELECT 1 FROM reviews_tbl r
                   WHERE r.product_id = op.product_id
                     AND r.customer_id = ?)";
$st = $conn->prepare($sql);
$st->bind_param("ii",$order_id,$customer_id);
$st->execute();
$res = $st->get_result();

$products=[];
while($row=$res->fetch_assoc()){
    $dir = "../assets/uploads/";
    $base = $row['product_id']."_".str_replace(" ","-",strtolower($row['name']))."_1";
    $img = "assets/logo-brown.png";
    foreach(['jpg','jpeg','png'] as $ext){
        if(file_exists($dir.$base.".".$ext)){ $img = "assets/uploads/".$base.".".$ext; break; }
    }
    $row['image'] = $img;
    $products[] = $row;
}
echo json_encode(['error'=>false,'products'=>$products]);
