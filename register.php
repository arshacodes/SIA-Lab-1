<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers | Sign up</title>
    <!-- <script src="js/jquery-3.7.1.js"></script>
    <script src="js/sweetalert@11.js"></script> -->
    <!-- <script src="js/scripts.js"></script> -->
</head>
<body class="user-page text-center py-4">
    <div class="card p-4 m-auto bg-white" style="width:80%; max-width:500px; height:100%; border:1px solid black;">
        <img src="pictures/logo-white.png" style="max-width: 75px" class="mb-4 mx-auto">
        <form id="signup-customer-form" action="functions/signup_customer.php" method="post" class="mb-4">
            <div class="row">
                <div class="col-sm-6 form-group text-start mb-4" style="color:#351B00">
                    <label for="new_customer_firstname">Firstname:</label>
                    <input type="text" id="new_customer_firstname" name="new_customer_firstname" class="form-control lexend-peta-12" required>
                </div>
                <div class="col-sm-6 form-group text-start mb-4" style="color:#351B00">
                    <label for="new_customer_lastname">Lastname:</label>
                    <input type="text" id="new_customer_lastname" name="new_customer_lastname" class="form-control lexend-peta-12" required>
                </div>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_email">Email:</label>
                <input type="email" id="new_customer_email" name="new_customer_email" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_phone">Phone:</label>
                <input type="tel" id="new_customer_phone" name="new_customer_phone" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_email">House:</label>
                <input type="text" id="new_customer_house" name="new_customer_house" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_phone">Street:</label>
                <input type="text" id="new_customer_street" name="new_customer_street" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_email">City:</label>
                <input type="text" id="new_customer_city" name="new_customer_city" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_phone">Province:</label>
                <input type="text" id="new_customer_province" name="new_customer_province" class="form-control lexend-peta-12" required>
            </div>
            <div class="form-group text-start mb-4" style="color:#351B00">
                <label for="new_customer_password">Password:</label>
                <input type="password" id="new_customer_password" name="new_customer_password" class="form-control lexend-peta-12" required>
            </div>
            <button type="submit" class="btn bg-brown text-cream">Sign up</button>
        </form>
        <p class="my-4 cormorant-upright-20"><a href="login.php"><u>Back to Login</u></a></p>
    </div>
    
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/sweetalert@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>