<?php
include '../db/db_conn.php';
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if(!isset($data['reviews']) || !is_array($data['reviews'])){
    echo json_encode(['error'=>true,'message'=>'Bad payload']); exit;
}
$customer_id = $_SESSION['user_id'] ?? 0;
$saved = 0;

$ins = $conn->prepare(
  "INSERT INTO reviews_tbl (product_id, customer_id, review_text, stars, date)
   VALUES (?,?,?,?,NOW())"
);
foreach($data['reviews'] as $r){
  $pid   = intval($r['product_id']);
  $stars = intval($r['stars']);
  $text  = trim($r['text']??'');

  if(!$pid || !$text || $stars<1 || $stars>5) continue;

  /* skip duplicates */
  $dup=$conn->prepare("SELECT 1 FROM reviews_tbl WHERE product_id=? AND customer_id=?");
  $dup->bind_param("ii",$pid,$customer_id);
  $dup->execute();
  if($dup->get_result()->num_rows) continue;

  $ins->bind_param("iisi",$pid,$customer_id,$text,$stars);
  $ins->execute();  $saved++;
}
echo json_encode(['error'=>false,'saved'=>$saved]);
