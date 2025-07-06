<?php
include '../db/db_conn.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT id, firstname, lastname, email, phone FROM customers_tbl WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR phone LIKE ?";
$stmt = $conn->prepare($query);
$searchParam = "%" . $search . "%";
$stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['firstname']}</td>
            <td>{$row['lastname']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
        </tr>";
    }
} else {
    $output = "<tr><td colspan='5'>No matching records found.</td></tr>";
}

echo $output;
$conn->close();
?>
