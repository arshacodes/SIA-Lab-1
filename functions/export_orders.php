<?php
require '../vendor/autoload.php';
include '../db/db_conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = "
  SELECT o.id,
         o.reference_number,
         CONCAT(c.firstname,' ',c.lastname)          AS customer_name,
         FORMAT(o.price_total,2)                     AS total,
         IFNULL(cs.name,'Unknown')                   AS courier,
         o.address,
         o.order_date,
         o.required_date,
         os.name                                     AS status
    FROM orders_tbl          o
    LEFT JOIN customers_tbl  c  ON c.id  = o.customer_id
    LEFT JOIN courier_services_tbl cs ON cs.id = o.courier_service_id
    LEFT JOIN order_status_tbl os  ON os.id = o.status_id
  ORDER BY o.id";
$res = $conn->query($sql);

$sheet = (new Spreadsheet())->getActiveSheet();
$sheet->setCellValue('A1','ID')
      ->setCellValue('B1','Ref No.')
      ->setCellValue('C1','Customer')
      ->setCellValue('D1','Total')
      ->setCellValue('E1','Courier')
      ->setCellValue('F1','Address')
      ->setCellValue('G1','Order Date')
      ->setCellValue('H1','Required Date')
      ->setCellValue('I1','Status');

$r = 2;
while ($row = $res->fetch_assoc()) {
    $sheet->setCellValue("A$r",$row['id'])
          ->setCellValue("B$r",$row['reference_number'])
          ->setCellValue("C$r",$row['customer_name'])
          ->setCellValue("D$r",$row['total'])
          ->setCellValue("E$r",$row['courier'])
          ->setCellValue("F$r",$row['address'])
          ->setCellValue("G$r",$row['order_date'])
          ->setCellValue("H$r",$row['required_date'])
          ->setCellValue("I$r",$row['status']);
    $r++;
}

$conn->close();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="orders.xlsx"');
header('Cache-Control: max-age=0');

(new Xlsx($sheet->getParent()))->save('php://output');
exit;
?>
