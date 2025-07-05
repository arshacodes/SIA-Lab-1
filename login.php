<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">    <link rel="stylesheet" href="css/styles.css">
    <!-- <link rel="stylesheet" href="css/flip_card_styles.css"> -->
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers | Log in</title>
    <!-- <script src="js/jquery-3.7.1.js"></script> -->
    <script src="js/sweetalert@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <!-- <script src="js/scripts.js"></script> -->
</head>
<body class="user-page py-4" style="max-height: 100%">
    <div class="flip-card container-fluid py-5">
        <div class="flip-card-inner" id="flip-card-inner">
            <div class="flip-card-front rounded p-4 my-auto" style="background-color: rgba(255, 255, 255, 0.75); border: 1px solid black;">
                <img src="pictures/logo-white.png" style="max-width: 75px" class="mb-4">
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
                <p class="my-4 cormorant-upright-20"><a id="signup-customer-btn" href="signup.php"><u>Create Account</u></a> | <a id="admin-login-btn" href=""><u>Admin Login</u></a></p>
            </div>
            <!--Admin Login-->
        </div>
    </div>
</body>