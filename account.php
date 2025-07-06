<?php
include 'db/db_conn.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT firstname, lastname, email, phone, address_house, address_street, address_city, address_province, password FROM customers_tbl WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers | Account</title>
</head>
<body class="bg-cream">
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
            <div class="col p-0 text-end lexend-peta-12">
                Welcome, <?php echo htmlspecialchars($row['firstname']); ?>!
            </div>
        </div>
        <hr>
    </nav>

    <div class="card m-4 p-4 text-brown border-0 mx-auto" style="width: 80%; max-width:500px">
        <p class="lexend-peta-20 my-4"><b>Account Details</b></p>
        <form id="update-account-form">
            <div class="mb-4">
                <label><b>Firstname:</b></label>
                <input type="text" name="firstname" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['firstname']); ?>">
            </div>
            <div class="mb-4">
                <label><b>Lastname:</b></label>
                <input type="text" name="lastname" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['lastname']); ?>">
            </div>
            <div class="mb-4">
                <label><b>Email:</b></label>
                <input type="text" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['email']); ?>" disabled>
            </div>
            <div class="mb-4">
                <label><b>Phone:</b></label>
                <input type="text" name="phone" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['phone']); ?>">
            </div>
            <div class="mb-4">
                <label><b>House:</b></label>
                <input type="text" name="address_house" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['address_house']); ?>">
            </div>
            <div class="mb-4">
                <label><b>Street:</b></label>
                <input type="text" name="address_street" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['address_street']); ?>">
            </div>
            <div class="mb-4">
                <label><b>City:</b></label>
                <input type="text" name="address_city" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['address_city']); ?>">
            </div>
            <div class="mb-4">
                <label><b>Province:</b></label>
                <input type="text" name="address_province" class="form-control lexend-peta-12" value="<?php echo htmlspecialchars($row['address_province']); ?>">
            </div>
            <div class="mb-4">
                <label><b>Password:</b></label>
                <input type="password" class="form-control" value="********" disabled>
                <button type="button" class="m-0 btn p-0 lexend-peta-12" id="change-password-btn">Change Password</button>
            </div>
            <button type="submit" class="btn bg-brown text-cream p-2 lexend-peta-12" style="width:100%">Save Changes</button>
        </form>
        <hr class="my-4">
        <button class="btn btn-danger p-2 lexend-peta-12 mb-4" id="delete-customer-btn" style="width:100%" data-customer-id="<?php echo $_SESSION['user_id']; ?>">Delete Account</button>
        <button class="btn text-brown mb-4 p-2 lexend-peta-12" id="logout-btn" style="width:100%; border-color:#351B00">Logout</button>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="change-password-form">
                        <div class="mb-4">
                            <label><b>Current Password:</b></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label><b>New Password:</b></label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label><b>Confirm New Password:</b></label>
                            <input type="password" name="confirm_password" class="form-control mb-4" required>
                        </div>
                        <button type="submit" class="btn bg-brown text-cream p-2 lexend-peta-12" style="width:100%">Save New Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/sweetalert@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="./js/jquery-3.7.1.js"></script>
    <script src="./js/scripts.js"></script>
</body>
</html>
