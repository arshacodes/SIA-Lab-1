<?php
include 'db/db_conn.php';
include 'templates/header.php';
?>

<body">
<div class="container-fluid p-0">
    <img src="pictures/tagline.png" alt="tagline_banner" style="max-width:100%" class="m-0">
</div>
<div id="just-in-section" class="px-4 text-brown">
    <p class="lexend-peta-20 my-4 text-center">Just In</p>
    <div class="row mx-auto mb-4 justify-content-center d-flex flex-wrap" style="width:content">
        <?php
            $query = "SELECT id, name, shelf_price FROM products_tbl ORDER BY created_at DESC LIMIT 4";
            $result = $conn->query($query);
            
            while ($row = $result->fetch_assoc()):
                $image_dir = "pictures/content/";
                $base_filename = $row['id'] . "_" . str_replace(" ", "-", strtolower($row['name'])) . "_1";

                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                $image_filename = '';

                foreach ($allowed_extensions as $ext) {
                    if (file_exists($image_dir . $base_filename . "." . $ext)) {
                        $image_filename = $image_dir . $base_filename . "." . $ext;
                        break;
                    }
                }
                if (!$image_filename) {
                    $image_filename = "pictures/logo-brown.png";
                }
        ?>
        <!-- paayos nung responsive kineme, kasi minsan tatluhan nalabas. dapat either 4 in a (for desktop), 2 in a row (for tablet) and 1 in a row (for mobile)-->
        <a href="product.php?id=<?php echo $row['id']; ?>" class="col-sm-6 col-md-6 col-lg-4 px-2" style="width:100%; max-width:270px">
            <div class="card rounded-0 p-0 mx-auto border-0 text-brown" style="width:100%; height:100%; background-color:transparent">
                <div class="mb-4" style="height:100%; width:100%">
                    <img src="<?php echo $image_filename; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100%; height:100%; max-height:270px; max-width:270px; object-fit:cover" class="rounded">
                </div>    
                <div style="max-width:270px">
                    <p class="lexend-peta-20"><?php echo htmlspecialchars($row['name']); ?></p>
                    <p class="cormorant-upright-20">P<?php echo number_format((float)$row['shelf_price'], 2); ?></p>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
</div>
<div id="categories-section" class="bg-brown py-5">
    <div class="container">
        <div class="bags">
            <div class="category-item">
                <img src="./pictures/content/16_crimson-carry_1.jpg" class="category-img">
                <div class="category-label">Sling Bags</div>
            </div>
            <div class="category-item">
                <img src="./pictures/content/4_black-ember_1.jpg" class="category-img">
                <div class="category-label">Clutch Bags</div>
            </div>
            <div class="category-item">
                <img src="./pictures/content/3_cavern-black_1.png" class="category-img">
                <div class="category-label">Brief Case</div>
            </div>
            <div class="category-item tall">
                <img src="./pictures/content/14_golden-carry_1.png" class="category-img">
                <div class="category-label">Tote Bags</div>
            </div>
            <div class="category-item">
                <img src="./pictures/content/1_sand-bliss_1.jpg" class="category-img">
                <div class="category-label">Belt Bags</div>
            </div>
            <div class="category-item">
                <img src="./pictures/content/5_auburn-ridge_1.png" class="category-img">
                <div class="category-label">Backpacks</div>
            </div>
            <div class="category-item">
                <img src="./pictures/content/7_bronze-trail_1.png" class="category-img">
                <div class="category-label">Messenger Bags</div>
            </div>
        </div>
    </div>
</div>
<div id="testimonials-section" class="px-4 text-brown" style="background-color:#F6E5D1;">
    <p class="text-center m-0 p-4">No testimonials yet.</p>
</div>
<div id="best-sellers-section" class="px-4 text-brown">
    <p class="lexend-peta-20 my-4 text-center">Best Sellers</p>
    <div class="row mx-auto mb-4 justify-content-center d-flex flex-wrap" style="width:content">
        <?php
            $query = "SELECT id, name, shelf_price FROM products_tbl ORDER BY sales DESC LIMIT 4";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()):
                $image_dir = "pictures/content/";
                $base_filename = $row['id'] . "_" . str_replace(" ", "-", strtolower($row['name'])) . "_1";

                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                $image_filename = '';

                foreach ($allowed_extensions as $ext) {
                    if (file_exists($image_dir . $base_filename . "." . $ext)) {
                        $image_filename = $image_dir . $base_filename . "." . $ext;
                        break;
                    }
                }
                
                if (!$image_filename) {
                    $image_filename = "pictures/logo-brown.png";
                }
        ?>
        <!-- paayos nung responsive kineme, kasi minsan tatluhan nalabas. dapat either 4 in a (for desktop), 2 in a row (for tablet) and 1 in a row (for mobile)-->
        <a href="product.php?id=<?php echo $row['id']; ?>" class="col-sm-6 col-md-6 col-lg-3 px-2" style="width:100%; max-width:270px">
            <div class="card rounded-0 p-0 mx-auto border-0 text-brown" style="width:100%; height:100%; background-color:transparent">
                <div class="mb-4" style="height:100%; width:100%">
                    <img src="<?php echo $image_filename; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100%; height:100%; max-height:270px; max-width:270px; object-fit:cover" class="rounded">
                </div>    
                <div style="max-width:270px">
                    <p class="lexend-peta-20"><?php echo htmlspecialchars($row['name']); ?></p>
                    <p class="cormorant-upright-20">P<?php echo number_format((float)$row['shelf_price'], 2); ?></p>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
</div>

</body>

<?php include 'templates/footer.php'; ?>