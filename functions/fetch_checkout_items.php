<?php
include '../db/db_conn.php';
session_start();

$selected_cart_items = $_POST['selected_cart_items'];
$placeholders = implode(",", array_fill(0, count($selected_cart_items), "?"));

$query = "
    SELECT cp.id, p.name, p.buy_price, cp.product_id, cp.avail_engraving, cp.avail_giftbox, SUM(cp.quantity) AS quantity 
    FROM carts_products_tbl cp
    JOIN products_tbl p ON cp.product_id = p.id
    WHERE cp.product_id IN ($placeholders)
    GROUP BY cp.id, cp.product_id, cp.avail_engraving, cp.avail_giftbox";

$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("i", count($selected_cart_items)), ...$selected_cart_items);
$stmt->execute();
$result = $stmt->get_result();

$output = "<div class='container-fluid p-4'><div class='row'>";
while ($row = $result->fetch_assoc()) {
    $image_dir = "assets/uploads/";
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
        $image_filename = "assets/logo-brown.png";
    }

    $extra_cost = ($row['avail_engraving'] ? 200 : 0) + ($row['avail_giftbox'] == 1 ? 200 : 0);
    $total_price = ($row['buy_price'] + $extra_cost) * $row['quantity'];

    $output .= "
        <div class='col-md-6 d-flex align-items-stretch mb-4'>
            <div class='card border-0 p-4 w-100 h-100' style='color:#351B00'>
                <div class='row'>
                    <div class='col-lg-6 my-auto mx-auto' style='max-width:300px'>
                        <img src='$image_filename' class='rounded' style='width:100%; max-height:400px; object-fit:cover'>
                    </div>
                    <div class='col-lg-6 my-auto'>
                        <p class='lexend-peta-20'><b>{$row['name']}</b></p>
                        <p><b>Item Price:</b> ₱" . number_format($row['buy_price'], 2) . "</p>
                        <p><b>Engraving:</b> " . ($row['avail_engraving'] ?: 'None') . "</p>
                        <p><b>Giftbox:</b> " . ($row['avail_giftbox'] == 1 ? 'Yes' : 'No') . "</p>
                        <p><b>Quantity:</b> {$row['quantity']}</p>
                        <p><b>Total Price:</b> ₱" . number_format($total_price, 2) . "</p>
                    </div>
                </div>
            </div>
        </div>";
}
$output .= "</div></div>";

$stmt->close();
$conn->close();

echo $output;
?>
