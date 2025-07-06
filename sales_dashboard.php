<?php
// insert code for verifying user role, before access. if yes, show this page. if no, tell them they have unauthorized access
include 'db/db_conn.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers | Log in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/dashboard_styles.css">
</head>
<body class="bg-cream lexend-peta-12 vh-100">
    <div class="">   
        <div class="content p-4" style="margin-left:200px; margin-top:50px">
            <div id="sales-dashboard-tabpage" class="tabpages m-0 p-0">
                <div class="row g-2">
                    <div class="col-12 col-sm-6 p-0 m-0">
                        <div class="card p-4">
                        </div>
                    </div>
                </div>
            </div>
            <div id="sales-invoices-tabpage" class="tabpages m-0 p-0">
                <!-- <div class="row g-2"> -->
                <div class="card p-4 m-0 border-0">
                    <div class="d-flex justify-content-between">
                        <span class="lexend-peta-20 card-title">Sales Invoices</span>
                        <span>
                            badges
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
            <div id="sales-orders-tabpage" class="tabpages m-0 p-0" style="display:block">
                <div class="card p-4 m-0 border-0">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="lexend-peta-20 card-title"><b>Orders</b></span>
                        <span>
                            badges
                        </span>
                    </div>
                    <div class="p-0 mb-2">
                        <input type="text" id="search-orders-box" placeholder="Search" class="form-control lexend-peta-12">
                    </div>
                    <!-- <hr class="mb-4"> -->
                    <table class="table bg-white">
                        <thead class="">
                            <tr class="">
                                <th class="lexend-peta-12">
                                    ID
                                    <button style="max-height:20px" class="sort-toggle btn btn-sm p-0" data-sort="id" data-dir="asc">⇅</button>
                                </th>
                                <th class="lexend-peta-12">
                                    Reference Code
                                    <button style="max-height:20px" class="sort-toggle btn btn-sm p-0" data-sort="reference_code" data-dir="asc">⇅</button>
                                </th>
                                <th class="lexend-peta-12">
                                    Customer Name
                                    <button style="max-height:20px" class="sort-toggle btn btn-sm p-0" data-sort="customer_name" data-dir="asc">⇅</button>
                                </th>
                                <th class="lexend-peta-12">
                                    Required Date
                                    <button style="max-height:20px" class="sort-toggle btn btn-sm p-0" data-sort="required_date" data-dir="asc">⇅</button>
                                </th>
                                <th class="lexend-peta-12 h-100">Status</th>
                                <th class="lexend-peta-12 h-100">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $statusOptions = [];
                                $statusQuery = $conn->query("SELECT o.id, o.name FROM order_status_tbl o");
                                while ($statusRow = $statusQuery->fetch_assoc()) {
                                    $statusOptions[] = $statusRow;
                                }

                                $sortColumn = $_GET['sort'] ?? 'o.id';
                                $sortDir = $_GET['dir'] ?? 'asc';

                                $validCols = ['o.id', 'o.reference_code', 'customer_name', 'o.required_date'];
                                if (!in_array($sortColumn, $validCols)) $sortColumn = 'id';
                                if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'asc';

                                $sql = "
                                SELECT 
                                    o.id AS order_id,
                                    o.reference_code,
                                    o.customer_id,
                                    o.required_date,
                                    o.order_status,
                                    CONCAT(c.firstname, ' ', c.lastname) AS customer_name,
                                    s.name AS order_status_name
                                FROM orders_tbl o
                                JOIN customers_tbl c ON o.customer_id = c.id
                                JOIN order_status_tbl s ON o.order_status = s.id
                                ORDER BY $sortColumn $sortDir
                                ";

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["order_id"] . "</td>";
                                    echo "<td>" . $row["reference_code"] . "</td>";
                                    echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row["required_date"])) . "</td>";

                                    echo "<td>";
                                    echo "<select class='form-select form-select-sm update-status btn-outline-brown lexend-peta-12' data-order-id='" . $row["order_id"] . "'>";
                                    foreach ($statusOptions as $option) {
                                    $selected = ($row["order_status"] == $option["id"]) ? "selected" : "";
                                    echo "<option value='" . $option["id"] . "' $selected>" . htmlspecialchars($option["name"]) . "</option>";
                                    }
                                    echo "</select></td>";
                            ?>

                                    <td>
                                        <button class="btn view-order-details bg-brown text-cream rounded lexend-peta-12 p-1"
                                            data-order-id="<?php echo $order['id']; ?>"
                                            data-reference="<?php echo $order['reference_number']; ?>"
                                            data-customer="<?php echo $order['customer_name']; ?>"
                                            data-total="<?php echo $order['price_total']; ?>"
                                            data-courier="<?php echo $order['courier_service_id']; ?>"
                                            data-address="<?php echo $order['address']; ?>"
                                            data-order-date="<?php echo $order['order_date']; ?>"
                                            data-required-date="<?php echo $order['required_date']; ?>"
                                            data-comments="<?php echo htmlspecialchars($order['comments']); ?>">
                                            View Details
                                        </button>
                                    </td>

                            <?php
                                    echo "</tr>";
                                }
                                } else {
                                echo "<tr><td colspan='6'>No orders found.</td></tr>";
                                }
                                $conn->close();
                            ?>

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button id="export-orders" class="btn bg-brown text-cream lexend-peta-12">Export</button>
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
        <div class="topbar z-1 bg-white text-brown lexend-peta-12 text-end fixed-top" style="height:50px">
            <div class="px-4 d-flex align-items-center justify-content-end h-100">
                <div class="">
                    <i class="bi-person-circle text-brown"></i>
                <span class="ms-1">SAMPLE PANGALAN</span>
                </div>
            </div>
            <hr>
        </div> 
    </div>
    <div class="nav-panel vh-100 bg-brown text-cream lexend-peta-12 fixed-top z-2" style="max-width:200px">
        <div class="container-fluid d-flex" id="toggleSidebar" style="height:50px"><img src="pictures/logo-brown.png" class="m-auto" style="max-height:80%"></div>
            <ul class="container_fluid m-0 px-4 pt-4">
                <li class="container-fluid mb-2 p-0 d-flex">
                    <div id="sales-dashboard-tab" class="tabs">
                        <i class="bi-house text-cream"></i>                    
                        <span>Dashboard</span>
                    </div>
                </li>
                <li class="container-fluid mb-2 p-0 d-flex">
                    <div id="sales-invoices-tab" class="tabs">
                        <i class="bi-receipt text-cream"></i>                    
                        <span>Sales Invoices</span>
                    </div>
                </li>
                <li class="container-fluid mb-2 p-0 d-flex">
                    <div id="sales-orders-tab" class="tabs"> 
                        <i class="bi-box2 text-cream"></i>                   
                        <span>Orders</span>
                    </div>
                </li>
                <li class="container-fluid mb-2 p-0 d-flex">
                    <div id="sales-products-tab" class="tabs">   
                        <i class="bi-handbag text-cream"></i>                
                        <span>Products</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#351B00">Order Details</h5>
                </div>
                <div class="modal-body">
                    <p style="color:#351B00"><b>Reference No:</b> <span id="order_reference_number"></span></p>
                    <p style="color:#351B00"><b>Customer ID:</b> <span id="order_customer_name"></span></p>
                    <p style="color:#351B00"><b>Total:</b> <span id="order_price_total"></span></p>
                    <p style="color:#351B00"><b>Courier:</b> <span id="order_courier"></span></p>
                    <p style="color:#351B00"><b>Address:</b> <span id="order_address"></span></p>
                    <p style="color:#351B00"><b>Order Date:</b> <span id="order_date"></span></p>
                    <p style="color:#351B00"><b>Required Date:</b> <span id="order_required_date"></span></p>
                    <p style="color:#351B00"><b>Shipping Date:</b> <span id="order_shipping_date"></span></p>
                    <p style="color:#351B00"><b>Comments:</b> <span id="order_comments"></span></p>
                    <hr class="mb-4">
                    <p style="color:#351B00"><b>Ordered Products:</b></p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="color:#351B00">Product</th>
                                <th style="color:#351B00">Total</th>
                                <th style="color:#351B00">Quantity</th>
                                <!-- <th style="color:#351B00">Engraving</th>
                                <th style="color:#351B00">Giftbox</th> -->
                            </tr>
                        </thead>
                        <tbody id="order-products-tbody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-brown text-cream rounded lexend-peta-12 p-1" id="closeOrderDetailsModal">Close</button>
                </div>
            </div>
        </div>
    </div>


<?php

include 'templates/dashboard_footer.php';

?>