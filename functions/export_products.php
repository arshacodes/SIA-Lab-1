<?php
require '../vendor/autoload.php';
include '../db/db_conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = "SELECT id, name, description, quantity_in_stock, buy_price, (SELECT name FROM product_categories_tbl WHERE id = products_tbl.category) AS category_name, number_of_sales, release_date FROM products_tbl";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Name')
      ->setCellValue('C1', 'Description')
      ->setCellValue('D1', 'Stock')
      ->setCellValue('E1', 'Price')
      ->setCellValue('F1', 'Category')
      ->setCellValue('G1', 'Sales')
      ->setCellValue('H1', 'Release');

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", $row['id'])
          ->setCellValue("B$rowIndex", $row['name'])
          ->setCellValue("C$rowIndex", $row['description'])
          ->setCellValue("D$rowIndex", $row['quantity_in_stock'])
          ->setCellValue("E$rowIndex", $row['buy_price'])
          ->setCellValue("F$rowIndex", $row['category_name'])
          ->setCellValue("G$rowIndex", $row['number_of_sales'])
          ->setCellValue("H$rowIndex", $row['release_date']);
    $rowIndex++;
}

$conn->close();

$writer = new Xlsx($spreadsheet);
$filename = "products.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
