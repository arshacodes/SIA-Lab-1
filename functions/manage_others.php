<?php
/*  functions/manage_others.php
   ────────────────────────────
   Reusable endpoint for:
   • courier_services_tbl
   • product_categories_tbl
*/
include '../db/db_conn.php';
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';      // add | update | delete
$type   = $_POST['type']   ?? '';      // courier | category
$id     = intval($_POST['id']   ?? 0);
$name   = trim($_POST['name'] ?? '');

$table = $type === 'courier'  ? 'courier_services_tbl'
       :($type === 'category' ? 'product_categories_tbl' : '');

if (!$table) {
    echo json_encode(['error'=>true,'message'=>'Invalid table type.']); exit;
}

try {
    switch ($action) {
        case 'add':
            if ($name === '') throw new Exception('Name required.');
            $stmt = $conn->prepare("INSERT INTO $table (name) VALUES (?)");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            break;

        case 'update':
            if (!$id || $name === '') throw new Exception('Missing ID or name.');
            $stmt = $conn->prepare("UPDATE $table SET name=? WHERE id=?");
            $stmt->bind_param('si', $name, $id);
            $stmt->execute();
            break;

        case 'delete':
            if (!$id) throw new Exception('ID required for deletion.');
            $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            break;

        default:
            throw new Exception('Invalid action.');
    }

    echo json_encode(['error'=>false]);
} catch (Exception $e) {
    echo json_encode(['error'=>true,'message'=>$e->getMessage()]);
}
