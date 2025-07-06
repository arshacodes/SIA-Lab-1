<?php
require '../vendor/autoload.php';
include '../db/db_conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = "SELECT id, name, quantity_in_stock, (SELECT name FROM product_categories_tbl WHERE id = products_tbl.category) AS category_name FROM products_tbl";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Name')
      ->setCellValue('C1', 'Stock')
      ->setCellValue('D1', 'Category');

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", $row['id'])
          ->setCellValue("B$rowIndex", $row['name'])
          ->setCellValue("C$rowIndex", $row['quantity_in_stock'])
          ->setCellValue("D$rowIndex", $row['category_name']);
    $rowIndex++;
}

$conn->close();

$writer = new Xlsx($spreadsheet);
$filename = "inventory.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>