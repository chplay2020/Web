<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <!-- ========== PHẦN HIỂN THỊ SẢN PHẨM THEO DANH MỤC - Category products section ========== -->
   <section class="products">

      <h1 class="title">
         <?= !empty($category) ? htmlspecialchars($category) . ' products' : 'products categories'; ?>
      </h1>

      <div class="box-container">

         <?php
         // Kiểm tra và hiển thị sản phẩm theo danh mục
         if (!empty($products)) {
            foreach ($products as $product) {
         ?>
               <!-- Form để thêm sản phẩm vào giỏ hàng hoặc wishlist -->
               <form action="" class="box" method="POST">
                  <!-- Hiển thị giá sản phẩm -->
                  <div class="price">$<span><?= $product['price']; ?></span>/-</div>
                  <!-- Link xem chi tiết sản phẩm -->
                  <a href="/view_page?pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
                  <!-- Hình ảnh sản phẩm -->
                  <img src="uploaded_img/<?= $product['image']; ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                  <!-- Tên sản phẩm -->
                  <div class="name"><?= htmlspecialchars($product['name']); ?></div>

                  <!-- Các input ẩn để gửi thông tin sản phẩm -->
                  <input type="hidden" name="pid" value="<?= $product['id']; ?>">
                  <input type="hidden" name="p_name" value="<?= $product['name']; ?>">
                  <input type="hidden" name="p_price" value="<?= $product['price']; ?>">
                  <input type="hidden" name="p_image" value="<?= $product['image']; ?>">

                  <!-- Input số lượng sản phẩm -->
                  <input type="number" min="1" value="1" name="p_qty" class="qty">

                  <!-- Nút thêm vào wishlist và cart -->
                  <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
                  <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products available!</p>';
         }
         ?>

      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>