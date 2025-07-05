<?php
include 'db/db_conn.php';
session_start();

// if (!isset($_GET['id'])) { header("Location: shop.php"); exit(); }

// $isLoggedIn = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'customer';
// $accountUrl  = $isLoggedIn ? "account.php"         : "login.php";
// $cartUrl     = $isLoggedIn ? "cart.php"            : "login.php";
// $ordersUrl   = $isLoggedIn ? "orders_tracking.php" : "login.php";

$product_id = $_GET['id'];
$pStmt = $conn->prepare("SELECT * FROM products_tbl WHERE id = ?");
$pStmt->bind_param("i", $product_id);
$pStmt->execute();
$product = $pStmt->get_result()->fetch_assoc();
if (!$product) { header("Location: shop.php"); exit(); }

$imgDir = "assets/uploads/";
$base   = $product['id']."_".str_replace(" ","-",strtolower($product['name']))."_1";
foreach (['jpg','jpeg','png'] as $ext) {
    if (file_exists($imgDir.$base.'.'.$ext)) { $leadImg = $imgDir.$base.'.'.$ext; break; }
}
$leadImg = $leadImg ?? "assets/logo-brown.png";

$rSql = "
  SELECT r.review_text, r.stars, r.date,
         CONCAT(c.firstname,' ',LEFT(c.lastname,1),'.') AS customer_name
  FROM reviews_tbl r
  JOIN customers_tbl c ON c.id = r.customer_id
  WHERE r.product_id = ?
  ORDER BY r.date DESC";
$rStmt = $conn->prepare($rSql);
$rStmt->bind_param("i",$product_id);
$rStmt->execute();
$reviews = $rStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$reviewCount = count($reviews);
$avgStars = $reviewCount ? array_sum(array_column($reviews,'stars')) / $reviewCount : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($product['name']) ?> | Product Details</title>
<link rel="icon" href="assets/logo-white.png">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/styles.css">
<script src="js/jquery-3.7.1.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/sweetalert@11.js"></script>
</head>
<body class="bg-cream">

<nav class="navbar navbar-expand-md d-flex flex-column bg-white py-0">
  <div class="px-4 my-4 container-fluid row">
    <div class="col p-0 text-start"><a href="javascript:history.back()" class="ms-2 back-btn"><p class="lexend-peta-12">< Back</p></a></div>
    <div class="col p-0 text-center" style="max-width:fit-content"><a class="navbar-brand m-0" href="index.php"><img src="assets/logo-white.png" style="max-width:75px"></a></div>
    <div class="col p-0 text-end">
      <a href="<?= $accountUrl ?>" class="ms-2"><img src="assets/account.svg"></a>
      <a href="<?= $cartUrl    ?>" class="ms-2"><img src="assets/shopping-cart.svg"></a>
      <a href="<?= $ordersUrl  ?>" class="ms-2"><img src="assets/orders.svg"></a>
    </div>
  </div><hr>
</nav>

<div class="container-fluid p-4">
  <div class="row mx-auto my-4 justify-content-center px-4">
    <div class="col-md-6 text-end my-2" style="max-width:400px">
      <div id="product-gallery" class="overflow-auto">
        <?php
          $gallery=[];
          for($i=1;$i<=5;$i++){
            foreach(['jpg','jpeg','png'] as $ext){
              $path="$imgDir".$product['id']."_".str_replace(" ","-",strtolower($product['name']))."_{$i}.{$ext}";
              if(file_exists($path)){ $gallery[]=$path; break; }
            }
          }
          if(!$gallery) $gallery[]=$leadImg;
          foreach($gallery as $g) echo "<img src='$g' class='rounded product-image my-2' style='max-width:100%;object-fit:cover'>";
        ?>
      </div>
    </div>

    <div class="col-md-6 text-start my-4" style="max-width:400px">
      <p class="lexend-peta-20"><b><?= htmlspecialchars($product['name']) ?></b></p>
      <p class="cormorant-upright-20">₱<?= number_format($product['buy_price'],2) ?></p>
      <hr class="my-4">
      <form id="add_to_cart" method="post" action="functions/add_to_cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <p class="cormorant-upright-20 mb-4"><?= htmlspecialchars($product['description']) ?></p>

        <div class="form-group mb-4" style="color:#351B00">
          <label class="mb-2"><b>Add engraving text?</b><br>*Additional 200 pesos (optional)</label>
          <input type="text" name="avail_engraving" class="form-control lexend-peta-12" placeholder="ex: Maria">
        </div>

        <div class="form-group mb-4" style="color:#351B00">
          <label class="mb-2"><b>Add giftbox?</b><br>*Additional 200 pesos</label>
          <div class="form-check"><input class="form-check-input" type="radio" name="avail_giftbox" value="no" required><label class="form-check-label">No</label></div>
          <div class="form-check"><input class="form-check-input" type="radio" name="avail_giftbox" value="yes" required><label class="form-check-label">Yes</label></div>
        </div>

        <div class="form-group mb-4" style="color:#351B00">
          <label class="mb-2"><b>Quantity:</b></label>
          <input type="number" name="quantity" class="form-control lexend-peta-12" value="1" min="1" required>
        </div>

        <button class="btn bg-brown text-cream w-100 lexend-peta-12 mb-4">ADD TO CART</button>
      </form>
    </div>
  </div>
  <div class="row mx-auto my-4 justify-content-center" style="max-width:800px">
    <div class="mx-auto">
      <div class="card border-0 p-4 pb-0">
        <div class="bg-white mb-4" style="color:#351B00">
          <p class="m-0 mb-2 lexend-peta-20"><b>Customer Reviews</b></p>
          <small><?= $reviewCount ?> review<?= $reviewCount!=1?'s':'' ?> • Average <?= number_format($avgStars,1) ?>/5</small>
        </div>
        <hr class="mb-4">
        <div class="" style="max-height:400px; overflow-y:auto; color:#351B00">
          <?php if ($reviewCount): ?>
            <?php foreach ($reviews as $r): ?>
              <div class="mb-4">
                <div class="d-flex justify-content-between">
                  <span class="mb-2"><b><?= htmlspecialchars($r['customer_name']) ?></b></span>
                  <span>
                    <?= str_repeat('★',$r['stars']).str_repeat('☆',5-$r['stars']) ?>
                  </span>
                </div>
                <p class="mb-2 cormorant-upright-20"><?= nl2br(htmlspecialchars($r['review_text'])) ?></p>
                <small class="text-muted"><?= date('M j, Y',strtotime($r['date'])) ?></small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center text-muted m-0 pb-4">No reviews yet.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
</body>
</html>
