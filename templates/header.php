<?php
session_start();

$isLoggedIn = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'customer';

$accountUrl = $isLoggedIn ? "account.php" : "login.php";
$wishlistUrl = $isLoggedIn ? "wishlist.php" : "login.php";
$cartUrl = $isLoggedIn ? "cart.php" : "login.php";
$ordersUrl = $isLoggedIn ? "orders_tracking.php" : "login.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="pictures/logo-white.png">
    <title>LMN & ASH Leathers</title>
</head>
<body class="bg-cream lexend-peta-12">
    <nav class="navbar navbar-expand-md d-flex flex-column bg-white py-0">
        <div class="px-4 my-4 d-block container-fluid row container-fluid d-flex flex-row">
            <div class="col p-0 text-start">
                <button class="navbar-toggler me-2 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav_collapse" aria-controls="nav_collapse" aria-expanded="false" aria-label="Toggle navigation" style="border: none">
                    <img src="pictures/menu.svg">
                </button>
            </div>
            <div class="col p-0 text-center" style="max-width:fit-content">
                <a class="navbar-brand m-0" href="index.php">
                    <img src="pictures/logo-white.png" style="max-width: 75px; margin: auto">
                </a>
            </div>
            <div class="col p-0 text-end">
                <a href="<?php echo $accountUrl; ?>" class="ms-2" id="account">
                    <img src="pictures/account.svg">
                </a>
                <a href="<?php echo $cartUrl; ?>" class="ms-2" id="cart">
                    <img src="pictures/shopping-cart.svg">
                </a>
                <a href="<?php echo $ordersUrl; ?>" class="ms-2" id="cart">
                    <img src="pictures/orders.svg">
                </a>
            </div>
        </div>
        <hr>
        <div class="collapse navbar-collapse px-4" id="nav_collapse" style="font-size:12px">
            <ul class="navbar-nav m-auto my-4">
                <li class="nav-item mx-1"><a class="nav-link py-0" href="index.php#just-in-section" style="color: #351B00">NEW</a></li>
                <li class="nav-item mx-1"><a class="nav-link py-0" href="shop.php" style="color: #351B00">SHOP ALL</a></li>
                <li class="nav-item mx-1"><a class="nav-link py-0" href="index.php#best-sellers-section" style="color: #351B00">SALE</a></li>
            </ul>
        </div>
    </nav>
    <div style="background-color:#351B00">
        <marquee class="blink text-center cormorant-upright-12" direction="up" scrollamount="1">HANDCRAFTED LEATHER BAGS | CUSTOMIZATION AVAILABLE</marquee>
    </div>