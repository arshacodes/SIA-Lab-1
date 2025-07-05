<?php
// insert code for verifying user role, before access. if yes, show this page. if no, tell them they have unauthorized access
include 'db/db_conn.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <!-- page title -->
    <title>LMN & ASH Leathers | Log in</title>
    <!-- css inserts, bootstrap css, icons, styles.css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/dashboard_styles.css">
</head>
<body class="bg-cream lexend-peta-12">
    <div class="sidebar bg-white text-brown lexend-peta-12 h-100">
        <div class="container-fluid p-4" id="sidebar-toggle"><img src="pictures/logo-white.png" class="logo"></div>
        <ul class="m-0 px-4">
            <li class="container-fluid mt-2">
                <div id="sales-dashboard-tab" class="tabs">                    
                    <i class="bi-house"></i>
                    <!-- <span class="ms-1">Dashboard</span> -->
                </div>
            </li>
            <li class="container-fluid mt-2">
                <div id="sales-invoices-tab" class="tabs">                    
                    <i class="bi-receipt"></i>
                    <!-- <span class="ms-1">Sales Invoice</span> -->
                </div>
            </li>
            <li class="container-fluid mt-2">
                <div id="sales-orders-tab" class="tabs">                    
                    <i class="bi-box2"></i>
                    <!-- <span class="ms-1">Orders</span> -->
                </div>
            </li>
            <li class="container-fluid mt-2">
                <div id="sales-products-tab" class="tabs">                    
                    <i class="bi-handbag"></i>
                    <!-- <span class="ms-1">Products</span> -->
                </div>
            </li>
        </ul>
    </div>
    <div class="fixed-top z-n1">
        <div class="topbar bg-white text-brown lexend-peta-12 h-100 text-end">
            <!-- <div class="container-fluid p-2" id="sidebar-toggle"><img src="pictures/logo-cream.png" class="logo"></div> -->
            <div class="p-4">                    
                <i class="bi-person-circle"></i>
                <span class="ms-1">SAMPLE PANGALAN</span>
            </div>
            <hr>
        </div>    
        <div class="content p-4">
            <div id="sales-dashboard-tabpage" class="tabpages m-0 p-0">
                <div class="row g-2">
                    <div class="col-12 col-sm-6 p-0 m-0">
                        <div class="card p-4">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div id="sales-invoices-tabpage" class="tabpages m-0 p-0" style="display:block">
                <!-- <div class="row g-2"> -->
                    <div class="card p-4 m-0">
                        <div class="d-flex justify-content-between">
                            <span class="lexend-peta-20 card-title">Sales Invoices</span>
                            <span>

                            </span>
                        </div>
<?php
                        $sql = "SELECT id, order_id, payment_status, payment_method, invoice_date FROM sales_invoices_tbl";
                        $result = $conn->query($sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["order_id"] . "</td>";
                                echo "<td>" . ucfirst($row["payment_status"]) . "</td>";
                                echo "<td>" . strtoupper($row["payment_method"]) . "</td>";
                                echo "<td>" . date("F j, Y", strtotime($row["invoice_date"])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No invoices found.</td></tr>";
                        }
?>
                    </div>
                <!-- </div> -->
            </div>
            <div id="sales-orders-tabpage" class="tabpages m-0 p-0">
                <div class="row g-2">
                    <div class="col-12 col-sm-6 p-0 m-0">
                        <div class="card p-4">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div id="sales-products-tabpage" class="tabpages m-0 p-0">
                <div class="row g-2">
                    <div class="col-12 col-sm-6 p-0 m-0">
                        <div class="card p-4">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php

$conn->close();

include 'templates/dashboard_footer.php';

?>