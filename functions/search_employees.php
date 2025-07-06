<?php
include '../db/db_conn.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT id, firstname, lastname, email, (SELECT name FROM departments_tbl WHERE id = employees_tbl.department) AS department_name FROM employees_tbl WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR department LIKE (SELECT id FROM departments_tbl WHERE name LIKE ?)";
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
            <td>{$row['department_name']}</td>
            <td>
                <div class='row my-auto mx-1'>
                    <button class='btn edit-employee-btn bg-brown text-cream rounded lexend-peta-12 p-1 mb-1'
                            data-id='{$row['id']}'
                            data-firstname='{$row['firstname']}'
                            data-lastname='{$row['lastname']}'
                            data-department='{$row['department_name']}'>
                        Edit
                    </button>
                    <button class='btn delete-employee-btn bg-white rounded lexend-peta-12 p-1' style='border-color:#351B00' data-id='{$row['id']}'>Delete</button>
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
