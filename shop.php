<?php
include 'templates/header.php'; 
include 'db/db_conn.php'; 

$category_query = "SELECT id, name FROM product_categories_tbl ORDER BY name ASC";
$category_result = $conn->query($category_query);

$query = "SELECT p.id, p.name, p.shelf_price,
                 p.stock,
                 c.name AS category_name,
                 p.product_category AS category_id
          FROM products_tbl p
          JOIN product_categories_tbl c ON p.product_category = c.id";

$result = $conn->query($query);
?>

<div class="px-4 my-4">
    <div class="row mx-auto mb-4 justify-content-center d-flex flex-wrap">
        <div class="col-md-6 mb-2">
            <input type="text" id="search-shop-box" placeholder="Search" class="form-control lexend-peta-12" style="color:#351B00">
        </div>
        <div class="col-md-6">
            <select id="category-filter" class="form-control lexend-peta-12" style="color:#351B00">
                <option value="">All Categories</option>
                <?php while ($category = $category_result->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="row mx-auto mb-4 justify-content-center d-flex flex-wrap" style="width:content">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
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
            <!-- edit mo yung code para yung mga nasa dulo, hindi gigitna, nasa left side sila naka-align. more details sa dc -->
            <a href="product.php?id=<?php echo $row['id']; ?>" class="col-sm-6 col-md-6 col-lg-3 px-2 product-item mb-4" data-category="<?php echo $row['category_id']; ?>" style="width:100%; max-width:270px">
                <div class="card rounded-0 p-0 mx-auto border-0 text-brown" style="width:100%; height:100%; background-color:transparent">
                    <div class="mb-4" style="height:100%; width:100%">
                        <img src="<?php echo $image_filename; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100%; height:100%; max-height:270px; max-width:270px; object-fit:cover" class="rounded">
                    </div>    
                    <div style="max-width:270px">
                        <p class="lexend-peta-20 product-name"><?php echo htmlspecialchars($row['name']); ?></p>
                        <p class="cormorant-upright-20 mb-0">P<?php echo number_format((float)$row['shelf_price'], 2); ?></p>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
