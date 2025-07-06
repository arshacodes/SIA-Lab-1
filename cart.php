<?php
session_start();
include 'db/db_conn.php';

$isLoggedIn = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'customer';
if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

$query = "
    SELECT ci.id, p.name, p.shelf_price, ci.product_id, SUM(ci.quantity) AS quantity 
    FROM cart_items_tbl ci
    JOIN products_tbl p ON ci.product_id = p.id
    WHERE ci.customer_id = ?
    GROUP BY ci.id, ci.product_id
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $image_dir = "pictures/content/";
    $base_filename = $row['product_id'] . "_" . str_replace(" ", "-", strtolower($row['name'])) . "_1";
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $image_filename = '';

    foreach ($allowed_extensions as $ext) {
        if (file_exists($image_dir . $base_filename . "." . $ext)) {
            $image_filename = $image_dir . $base_filename . "." . $ext;
            break;
        }
    }

    if (!$image_filename) {
        $image_filename = "pictures/logo-brown.png";
    }

    $total_price = $row['shelf_price'] * $row['quantity'];

    $cartItems[] = [
        'id' => $row['id'],
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'quantity' => $row['quantity'],
        'original_price' => $row['shelf_price'],
        'total_price' => $total_price,
        'image' => $image_filename
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="assets/logo-white.png">
    <title>Your Cart | LMN & ASH Leathers</title>
    <script src="js/sweetalert@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/cart.js"></script>
</head>
<body class="bg-cream text-brown lexend-peta-12">
    <nav class="navbar navbar-expand-md d-flex flex-column bg-white py-0">
        <div class="px-4 my-4 d-block container-fluid row d-flex flex-row">
            <div class="col p-0 text-start">
                <a href="javascript:history.back()" class="ms-2 back-btn">
                    <p class="lexend-peta-12">< Back</p>
                </a>
            </div>
            <div class="col p-0 text-center" style="max-width:fit-content">
                <a class="navbar-brand m-0" href="index.php">
                    <img src="pictures/logo-white.png" style="max-width: 75px; margin: auto">
                </a>
            </div>
            <div class="col p-0 text-end">
                <a href="account.php" class="ms-2" id="account">
                    <img src="pictures/account.svg">
                </a>
                <a href="cart.php" class="ms-2" id="cart">
                    <img src="pictures/shopping-cart.svg">
                </a>
                <a href="orders_tracking.php" class="ms-2" id="cart">
                    <img src="pictures/orders.svg">
                </a>
            </div>
        </div>
        <hr>
    </nav>
    <div class="container-fluid">
        <form id="checkout-form" class="m-0" autocomplete="off">
            <div class="row p-4 mt-4">
                <?php foreach ($cartItems as $item): ?>
                    <div class="col-md-6 d-flex align-items-stretch mb-4">
                        <div class="card border-0 p-4 w-100 h-100" style="color:#351B00">
                            <div class="d-flex flex-row my-auto">
                                <div class="my-auto me-4" style="height:fit-content; width:fit-content">
                                    <input type="checkbox" class="cart-checkbox" value="<?php echo $item['id']; ?>" data-id="<?php echo $item['id']; ?>" data-customer-id="<?php echo $customer_id; ?>" data-total-price=" <?php echo $item['total_price']; ?> ">
                                </div>
                                <div class="row w-100">
                                    <div class="col-lg-6 my-auto mx-auto" style="max-width:300px">
                                        <img src="<?php echo $item['image']; ?>" class="rounded" style="width:100%; max-height:400px; object-fit:cover">
                                    </div>
                                    <div class="col-lg-6 my-auto">
                                        <p class="lexend-peta-20"><b><?php echo htmlspecialchars($item['name']); ?></b></p>
                                        <p><b>Item Price:</b> ₱<?php echo number_format($item['original_price'], 2); ?></p>
                                        <p><b>Quantity:</b> <?php echo $item['quantity']; ?></p>
                                        <p><b>Total Price:</b> ₱<?php echo number_format($item['total_price'], 2); ?></p>
                                        <div class="row">
                                            <div class="col-md-6 p-2">
                                                <button type="button" class="btn bg-brown text-cream edit-item-btn p-1 rounded lexend-peta-12" style="width:100%"
                                                        data-id="<?php echo $item['id']; ?>" 
                                                        data-product-id="<?php echo $item['product_id']; ?>" 
                                                        data-quantity="<?php echo $item['quantity']; ?>">
                                                    Edit
                                                </button>
                                            </div>
                                            <div class="col-md-6 p-2">
                                                <button type="button" class="btn bg-white rounded p-1 delete-item-btn lexend-peta-12" style="width:100%; color:#351B00; border-color:#351B00"
                                                        data-id="<?php echo $item['id']; ?>">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="bg-white row fixed-bottom">
                <hr class="mb-4">
                <div class="mx-auto bg-white my-2" style="max-width:300px">
                    <p><b>Selected:</b> <span id="selected-count">0</span></p>
                    <p><b>Total Price:</b> ₱<span id="item-total-price">0.00</span></p>
                    <button type="submit" class="btn bg-brown text-cream w-100 mb-4">Checkout</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-item-form" method="post" action="functions/update_cart.php">
                        <input type="hidden" id="edit-product-id" name="product_id">
                        <input type="hidden" id="edit-cart-item-id" name="cart_item_id">
                        <div class="mb-3">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="number" id="edit-quantity" name="quantity" class="form-control" min="1">
                        </div>
                        <button type="submit" class="btn bg-brown text-cream w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutPreviewModal" tabindex="-1" inert>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="checkout-items-list" style="overflow-y:auto; max-height:500px">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="checkout-item card border-0 p-4 w-100 h-100" style="color:#351B00; display: none;" data-id="<?php echo $item['id']; ?>">
                                <div class="row">
                                    <div class="col-lg-6 my-auto mx-auto" style="max-width:300px">
                                        <img src="<?php echo $item['image']; ?>" class="rounded" style="width:100%; max-height:400px; object-fit:cover">
                                    </div>
                                    <div class="col-lg-6 my-auto">
                                        <p class="lexend-peta-20"><b><?php echo htmlspecialchars($item['name']); ?></b></p>
                                        <p><b>Item Price:</b> ₱<?php echo number_format($item['original_price'], 2); ?></p>
                                        <p><b>Quantity:</b> <?php echo $item['quantity']; ?></p>
                                        <p><b>Total Price:</b> ₱<?php echo number_format($item['total_price'], 2); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="mb-4">
                    <form id="checkout-preview-form" method="post" class="w-100">
                        <div class="m-0 mb-2 p-0" style="width:100%">
                            <label class="lexend-peta-12 d-block mb-2" style="color:#351B00" for="courier-service"><b>Courier Service:</b></label>
                            <select class="lexend-peta-12 w-100 mb-2 p-2" style="color:#351B00" id="courier-service" class="form-select">
                                <?php
                                // include '../db/db_conn.php';
                                $courierQuery = "SELECT id, name FROM courier_services_tbl WHERE status = 1";
                                $courierResult = $conn->query($courierQuery);
                                while ($row = $courierResult->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="m-0 mb-2 p-0" style="width:100%">
                            <label class="lexend-peta-12 d-block mb-2" style="color:#351B00"><b>Address:</b></label>
                            <p class="lexend-peta-12" style="color:#351B00">
                                <?php
                                $query = "SELECT address_house, address_street, address_city, address_province FROM customers_tbl WHERE id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("i", $customer_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($row = $result->fetch_assoc()) {
                                    echo htmlspecialchars(
                                        trim("{$row['address_house']} {$row['address_street']}, {$row['address_city']}, {$row['address_province']}")
                                    );
                                } else {
                                    echo "Address not found.";
                                }

                                $stmt->close();
                                ?>

                            </p>
                        </div>

                        <div class="m-0 mb-4 p-0" style="width:100%">
                            <label class="lexend-peta-12 d-block mb-2" style="color:#351B00" for="comments"><b>Comments:</b></label>
                            <textarea class="lexend-peta-12 w-100 p-2" style="color:#351B00" id="comments" class="form-control" placeholder="Add any special instructions"></textarea>
                        </div>
                        <p style="color:#351B00" class="lexend-peta-20"><b>Total Price:</b> ₱<span id="modal-total-price">0.00</span></p>
                        <hr class="mb-4">
                        <p class="text-danger text-center"><b>NO CANCELLATION OF ORDERS</b></p>
                        <button type="submit" class="btn bg-brown text-cream w-100">Confirm</button>
                    </form>
                 </div>
            </div>
        </div>
    </div>
    <!-- <script src="js/sweetalert@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/cart.js"></script> -->
</body>
</html>

