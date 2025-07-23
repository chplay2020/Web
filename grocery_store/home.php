<?php
// ========== TRANG CHỦ WEBSITE - HIỂN THỊ SẢN PHẨM ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id'] ?? null; // Lấy user_id từ session, null nếu không có

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
   exit();
};

// ========== XỬ LÝ THÊM VÀO WISHLIST ==========
if (isset($_POST['add_to_wishlist'])) {

   // Lấy và escape dữ liệu sản phẩm từ form
   $pid = $_POST['pid'];
   $pid = htmlspecialchars($pid, ENT_QUOTES, 'UTF-8');
   $p_name = $_POST['p_name'];
   $p_name = htmlspecialchars($p_name, ENT_QUOTES, 'UTF-8');
   $p_price = $_POST['p_price'];
   $p_price = htmlspecialchars($p_price, ENT_QUOTES, 'UTF-8');
   $p_image = $_POST['p_image'];
   $p_image = htmlspecialchars($p_image, ENT_QUOTES, 'UTF-8');

   // Kiểm tra sản phẩm đã có trong wishlist chưa
   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   // Kiểm tra sản phẩm đã có trong cart chưa
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if ($check_wishlist_numbers->rowCount() > 0) {
      $message[] = 'already added to wishlist!'; // Đã có trong wishlist
   } elseif ($check_cart_numbers->rowCount() > 0) {
      $message[] = 'already added to cart!'; // Đã có trong cart
   } else {
      // Thêm vào wishlist
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }
}

// ========== XỬ LÝ THÊM VÀO GIỎ HÀNG ==========
if (isset($_POST['add_to_cart'])) {

   // Lấy và escape dữ liệu sản phẩm từ form
   $pid = $_POST['pid'];
   $pid = htmlspecialchars($pid, ENT_QUOTES, 'UTF-8');
   $p_name = $_POST['p_name'];
   $p_name = htmlspecialchars($p_name, ENT_QUOTES, 'UTF-8');
   $p_price = $_POST['p_price'];
   $p_price = htmlspecialchars($p_price, ENT_QUOTES, 'UTF-8');
   $p_image = $_POST['p_image'];
   $p_image = htmlspecialchars($p_image, ENT_QUOTES, 'UTF-8');
   $p_qty = $_POST['p_qty']; // Số lượng sản phẩm
   $p_qty = htmlspecialchars($p_qty, ENT_QUOTES, 'UTF-8');

   // Kiểm tra sản phẩm đã có trong cart chưa
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if ($check_cart_numbers->rowCount() > 0) {
      $message[] = 'already added to cart!'; // Đã có trong cart
   } else {

      // Kiểm tra và xóa khỏi wishlist nếu có
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if ($check_wishlist_numbers->rowCount() > 0) {
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      // Thêm vào cart
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }
}

?>
<!-- ========== PHẦN HTML - GIAO DIỆN TRANG CHỦ ========== -->
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS cho trang chủ -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?> <!-- Include header với menu điều hướng -->

   <!-- ========== SECTION BANNER TRANG CHỦ ========== -->
   <div class="home-bg">

      <section class="home">

         <div class="content">
            <span>don't panic, go organice</span>
            <h3>Reach For A Healthier You With Organic Foods</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto natus culpa officia quasi, accusantium explicabo?</p>
            <a href="about.php" class="btn">about us</a>
         </div>

      </section>

   </div>

   <!-- ========== SECTION DANH MỤC SẢN PHẨM ========== -->
   <section class="home-category">

      <h1 class="title">shop by category</h1>

      <div class="box-container">

         <!-- Box danh mục Fruits -->
         <div class="box">
            <img src="images/cat-1.png" alt="">
            <h3>fruits</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=fruits" class="btn">fruits</a> <!-- Link đến trang danh mục fruits -->
         </div>

         <!-- Box danh mục Meat -->
         <div class="box">
            <img src="images/cat-2.png" alt="">
            <h3>meat</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=meat" class="btn">meat</a> <!-- Link đến trang danh mục meat -->
         </div>

         <!-- Box danh mục Vegetables -->
         <div class="box">
            <img src="images/cat-3.png" alt="">
            <h3>vegitables</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=vegitables" class="btn">vegitables</a> <!-- Link đến trang danh mục vegetables -->
         </div>

         <!-- Box danh mục Fish -->
         <div class="box">
            <img src="images/cat-4.png" alt="">
            <h3>fish</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=fish" class="btn">fish</a> <!-- Link đến trang danh mục fish -->
         </div>

      </div>

   </section>

   <!-- ========== SECTION SẢN PHẨM MỚI NHẤT ========== -->
   <section class="products">

      <h1 class="title">latest products</h1>

      <div class="box-container">

         <?php
         // Lấy 6 sản phẩm mới nhất từ database
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            // Lặp qua từng sản phẩm và hiển thị
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <!-- Form cho mỗi sản phẩm -->
               <form action="" class="box" method="POST">
                  <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div> <!-- Hiển thị giá -->
                  <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> <!-- Link xem chi tiết -->
                  <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""> <!-- Hiển thị ảnh sản phẩm -->
                  <div class="name"><?= $fetch_products['name']; ?></div> <!-- Hiển thị tên sản phẩm -->

                  <!-- Các input hidden chứa thông tin sản phẩm -->
                  <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                  <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
                  <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
                  <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">

                  <!-- Input số lượng -->
                  <input type="number" min="1" value="1" name="p_qty" class="qty">

                  <!-- Nút thêm vào wishlist -->
                  <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">

                  <!-- Nút thêm vào cart -->
                  <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>'; // Thông báo nếu chưa có sản phẩm
         }
         ?>

      </div>

   </section>

   <?php include 'footer.php'; ?> <!-- Include footer -->

   <script src="js/script.js"></script> <!-- Include JavaScript -->

</body>

</html>