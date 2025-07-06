<?php
require '../vendor/autoload.php';
include '../db/db_conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = "SELECT id, firstname, lastname, email, phone FROM customers_tbl";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Firstname')
      ->setCellValue('C1', 'Lastname')
      ->setCellValue('D1', 'Email')
      ->setCellValue('E1', 'Phone');

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", $row['id'])
          ->setCellValue("B$rowIndex", $row['firstname'])
          ->setCellValue("C$rowIndex", $row['lastname'])
          ->setCellValue("D$rowIndex", $row['email'])
          ->setCellValue("E$rowIndex", $row['phone']);
    $rowIndex++;
}

$conn->close();

$writer = new Xlsx($spreadsheet);
$filename = "customers.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
