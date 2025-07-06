<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/flip_card_styles.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers | Log in</title>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/sweetalert@11.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</head>
<body class="bg-cream text-center py-4" style="max-height: 100%">
    <img src="pictures/logo-cream.png" style="max-width: 75px" class="mb-4">
    <div class="flip-card container-fluid">
        <div class="flip-card-inner" id="flip-card-inner">
            <div class="flip-card-front bg-white rounded p-4 my-auto">
                <p class="lexend-peta-20 my-4">Login</p>
                <form id="customer-login-form" action="functions/customer_login.php" method="post" class="my-4">
                    <div class="form-group text-start mb-4">
                        <label for="customer_email">Email:</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control lexend-peta-12" required>
                    </div>
                    <div class="form-group text-start mb-4">
                        <label for="customer_password">Password:</label>
                        <input type="password" id="customer_password" name="customer_password" class="form-control lexend-peta-12" required>
                    </div>
                    <button type="submit" class="btn bg-brown text-cream">Login</button>
                </form>
                <p class="my-4 cormorant-upright-20"><a id="signup-customer-btn" href=""><u>Create Account</u></a> | <a id="admin-login-btn" href=""><u>Admin Login</u></a></p>
            </div>
            <div class="flip-card-back bg-white rounded p-4 my-auto">
                <p class="lexend-peta-20 my-4">Admin Login</p>
                <form id="admin-login-form" action="functions/admin_login.php" method="post" class="my-4">
                    <div class="form-group text-start mb-4">
                        <label for="admin_email">Email:</label>
                        <input type="email" id="admin_email" name="admin_email" class="form-control lexend-peta-12" required>
                    </div>
                    <div class="form-group text-start mb-4">
                        <label for="admin_password">Password:</label>
                        <input type="password" id="admin_password" name="admin_password" class="form-control lexend-peta-12" required>
                    </div>
                    <button type="submit" class="btn bg-brown text-cream">Login</button>
                </form>
                <p class="my-4 cormorant-upright-20"><a id="customer-login-btn" href=""><u>Customer Login</u></a></p>
            </div>
        </div>
    </div>
</body>