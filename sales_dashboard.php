<?php
// insert code for verifying user role, before access. if yes, show this page. if no, tell them they have unauthorized access
include 'db/db_conn.php';

$to_ship_query = "
  SELECT COUNT(*) AS to_ship_count
  FROM orders_tbl o
  JOIN order_status_tbl s ON o.order_status = s.id
  WHERE o.order_status = 1
";

$to_ship_result = $conn->query($to_ship_query);
$to_ship_data = $to_ship_result->fetch_assoc();
$toShipCount = $to_ship_data['to_ship_count'];

$to_receive_query = "
  SELECT COUNT(*) AS to_receive_count
  FROM orders_tbl o
  JOIN order_status_tbl s ON o.order_status = s.id
  WHERE o.order_status = 2
";

$to_receive_result = $conn->query($to_receive_query);
$to_receive_data = $to_receive_result->fetch_assoc();
$toReceiveCount = $to_receive_data['to_receive_count'];

$received_query = "
  SELECT COUNT(*) AS received_count
  FROM orders_tbl o
  JOIN order_status_tbl s ON o.order_status = s.id
  WHERE o.order_status = 3
";

$received_result = $conn->query($received_query);
$received_data = $received_result->fetch_assoc();
$receivedCount = $received_data['received_count'];

$orders_query = "
     SELECT 
        o.id AS order_id,
        o.reference_code,
        o.total_amount,
        o.courier_service,
        o.customer_id,
        o.checkout_date,
        o.required_date,
        o.shipping_date,
        o.order_status,
        o.comments,
        CONCAT(o.address_house, ' ', o.address_street, ' ', o.address_city, ' ', o.address_province) AS address,
        CONCAT(c.firstname, ' ', c.lastname) AS customer_name,
        s.name AS order_status_name
    FROM orders_tbl o
    JOIN customers_tbl c ON o.customer_id = c.id
    JOIN order_status_tbl s ON o.order_status = s.id
    JOIN courier_services_tbl cs ON o.courier_service = cs.id
    ORDER BY order_id
";

$orders_result = $conn->query($orders_query);


$products_query = "
    SELECT 
        p.id AS product_id,
        p.name,
        p.product_category,
        p.shelf_price,
        p.description,
        p.sales
    FROM products_tbl p
    JOIN product_categories_tbl c ON p.product_category = c.id
    ORDER BY product_id
";

$products_result = $conn->query($products_query);
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
                        <span class="d-flex flex-row">
                            <span class="p-2 text-brown"><b>To Ship: </b><?php echo htmlspecialchars($toShipCount); ?></span>
                            <span class="p-2 text-brown"><b>To Receive: </b><?php echo htmlspecialchars($toReceiveCount); ?></span>
                            <span class="p-2 text-brown"><b>Received: </b><?php echo htmlspecialchars($receivedCount); ?></span>
                        </span>
                    </div>
                    <div class="p-0 mb-2">
                        <input type="text" id="search-orders-box" placeholder="Search" class="form-control lexend-peta-12">
                    </div>
                    <!-- <hr class="mb-4"> -->
                    <table class="table bg-white">
                        <thead class="">
                            <tr class="">
                                <th class="lexend-peta-12">ID</th>
                                <th class="lexend-peta-12">Reference Code</th>
                                <th class="lexend-peta-12">Customer Name</th>
                                <th class="lexend-peta-12">Required Date</th>
                                <th class="lexend-peta-12">Status</th>
                                <th class="lexend-peta-12">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($orders_result->num_rows > 0):
                                while ($order = $orders_result->fetch_assoc()): ?>
                                    <tr>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($order['reference_code']); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($order['customer_name'], 2); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($order['required_date']); ?></td>
                                        <td>
                                            <select class="form-select update-order-status lexend-peta-12 btn-outline-brown"
                                                    data-order-id="<?= $order['order_id']; ?>">
                                                <?php
                                                $statusRes = $conn->query("SELECT * FROM order_status_tbl ORDER BY id");
                                                while ($s = $statusRes->fetch_assoc()):
                                                    // $sel = $s['id'] == $order['status_id'] ? 'selected' : '';
                                                    $sel = $s['id'] == $order['order_status'] ? 'selected' : '';
                                                    $dis = $s['id'] == 3                  ? 'disabled'  : '';
                                                ?>
                                                    <option value="<?= $s['id']; ?>" <?= "$sel $dis" ?>>
                                                        <?= htmlspecialchars($s['name']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </td>

                                        <td>
                                            <button class="btn view-order-details bg-brown text-cream rounded lexend-peta-12 p-1"
                                                data-order-id="<?php echo $order['order_id']; ?>"
                                                data-reference-code="<?php echo $order['reference_code']; ?>"
                                                data-customer-name="<?php echo $order['customer_name']; ?>"
                                                data-total-amount="<?php echo $order['total_amount']; ?>"
                                                data-courier-service="<?php echo $order['courier_service']; ?>"
                                                data-address="<?php echo $order['address']; ?>"
                                                data-checkout-date="<?php echo $order['checkout_date']; ?>"
                                                data-required-date="<?php echo $order['required_date']; ?>"
                                                data-shipping-date="<?php echo $order['shipping_date']; ?>"
                                                data-comments="<?php echo htmlspecialchars($order['comments']); ?>">
                                            View Details
                                        </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" style="text-align:center; color:red;">No orders available.</td></tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                    <!-- <div class="d-flex justify-content-end">
                        <button id="export-orders" class="btn bg-brown text-cream lexend-peta-12">Export</button>
                    </div> -->
                </div>
            </div>
            <div id="sales-products-tabpage" class="tabpages m-0 p-0">
                <div class="card p-4 m-0 border-0">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="lexend-peta-20 card-title"><b>Products</b></span>
                        <!-- <span class="d-flex flex-row">
                            <span class="p-2 text-brown"><b>To Ship: </b>?php echo htmlspecialchars($toShipCount); ?></span>
                            <span class="p-2 text-brown"><b>To Receive: </b>?php echo htmlspecialchars($toReceiveCount); ?></span>
                            <span class="p-2 text-brown"><b>Received: </b>?php echo htmlspecialchars($receivedCount); ?></span>
                        </span> -->
                    </div>
                    <div class="p-0 mb-2">
                        <input type="text" id="search-products-box" placeholder="Search" class="form-control lexend-peta-12">
                    </div>
                    <!-- <hr class="mb-4"> -->
                    <table class="table bg-white">
                        <thead class="">
                            <tr class="">
                                <th class="lexend-peta-12">ID</th>
                                <th class="lexend-peta-12">Product</th>
                                <th class="lexend-peta-12">Category</th>
                                <th class="lexend-peta-12">Price (₱)</th>
                                <th class="lexend-peta-12" style="width: 700px">Description</th>
                                <th class="lexend-peta-12">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($products_result->num_rows > 0):
                                while ($product = $products_result->fetch_assoc()): ?>
                                    <tr>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($product['product_id']); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($product['product_category'], 2); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($product['shelf_price']); ?></td>
                                        <td style="color:#351B00" style="width: 700px"><?php echo htmlspecialchars($product['description'], 2); ?></td>
                                        <td style="color:#351B00"><?php echo htmlspecialchars($product['sales'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" style="text-align:center; color:red;">No products available.</td></tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                    <!-- <div class="d-flex justify-content-end">
                        <button id="export-orders" class="btn bg-brown text-cream lexend-peta-12">Export</button>
                    </div> -->
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