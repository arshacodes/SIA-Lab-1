<?php
include '../db/db_conn.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT id, name, description, quantity_in_stock, buy_price, (SELECT name FROM product_categories_tbl WHERE id = products_tbl.category) AS category_name, number_of_sales FROM products_tbl WHERE name LIKE ? OR description LIKE ? OR quantity_in_stock LIKE ? OR buy_price LIKE ? OR category LIKE (SELECT id FROM product_categories_tbl WHERE name = ?) OR number_of_sales LIKE ?";
$stmt = $conn->prepare($query);
$searchParam = "%" . $search . "%";
$stmt->bind_param("ssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['description']}</td>
            <td>{$row['quantity_in_stock']}</td>
            <td>{$row['buy_price']}</td>
            <td>{$row['category_name']}</td>
            <td>{$row['number_of_sales']}</td>
            <td>
                <div class='row my-auto mx-1'>
                    <button class='btn edit-product-btn bg-brown text-cream rounded lexend-peta-12 mb-1 p-1'
                            data-id='{$row['id']}'
                            data-name='{$row['name']}'
                            data-description='{$row['description']}'
                            data-quantity_in_stock='{$row['quantity_in_stock']}'
                            data-buy_price='{$row['buy_price']}'
                            data-category_name='{$row['category_name']}'
                            data-number_of_sales='{$row['number_of_sales']}'>
                        Edit
                    </button>
                    <button class='btn delete-product-btn bg-white rounded lexend-peta-12 p-1' style='border-color:#351B00' data-id='{$row['id']}'>Delete</button>
                </div>
            </td>
        </tr>";
    }
} else {
    $output = "<tr><td colspan='5'>No matching records found.</td></tr>";
}

echo $output;
$conn->close();
?>
