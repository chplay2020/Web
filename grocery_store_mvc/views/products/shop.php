<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <!-- ========== PHẦN DANH MỤC SẢN PHẨM - Category navigation ========== -->
   <section class="p-category">

      <!-- Links điều hướng đến các danh mục sản phẩm -->
      <a href="/category?category=fruits">fruits</a>
      <a href="/category?category=vegitables">vegetables</a>
      <a href="/category?category=fish">fish</a>
      <a href="/category?category=meat">meat</a>

   </section>

   <!-- ========== PHẦN HIỂN THỊ DANH SÁCH SẢN PHẨM - Products display section ========== -->
   <section class="products">

      <h1 class="title">latest products</h1>

      <div class="box-container">

         <?php
         // Kiểm tra và hiển thị danh sách sản phẩm
         if (!empty($products)) {
            foreach ($products as $product) {
         ?>
               <!-- Form để thêm sản phẩm vào giỏ hàng hoặc wishlist -->
               <form action="" class="box" method="POST">
                  <!-- Hiển thị giá sản phẩm -->
                  <div class="price">$<span><?= $product['price']; ?></span>/-</div>
                  <!-- Link xem chi tiết sản phẩm -->
                  <a href="/view_page?pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
                  <!-- Hình ảnh sản phẩm với kích thước chuẩn hóa -->
                  <img src="uploaded_img/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                  <!-- Tên sản phẩm -->
                  <div class="name"><?= $product['name']; ?></div>

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
            // Hiển thị thông báo khi không có sản phẩm
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>