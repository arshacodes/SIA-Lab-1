<?php
include '../db/db_conn.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT id, name, quantity_in_stock, (SELECT name FROM product_categories_tbl WHERE id = products_tbl.category) AS category_name FROM products_tbl WHERE name LIKE ? OR category LIKE (SELECT id FROM product_categories_tbl WHERE name = ?)";
$stmt = $conn->prepare($query);
$searchParam = "%" . $search . "%";
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td style='color:#351B00'>{$row['id']}</td>
            <td style='color:#351B00'>{$row['name']}</td>
            <td style='color:#351B00'>{$row['quantity_in_stock']}</td>
            <td style='color:#351B00'>{$row['category_name']}</td>
            <td>
                <div class='row my-auto mx-1'>
                    <button class='btn edit-inventory-btn bg-brown text-cream rounded lexend-peta-12 mb-1 p-1'
                            data-id='{$row['id']}'
                            data-name='{$row['name']}'
                            data-quantity_in_stock='{$row['quantity_in_stock']}'
                            data-category_name='{$row['category_name']}'>
                        Edit
                    </button>
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
