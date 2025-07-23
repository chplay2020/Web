<?php
// ========== TRANG TÌM KIẾM SẢN PHẨM ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
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

// ========== XỬ LÝ THÊM VÀO CART ==========
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
         $delete_wishlist->execute([$p_name, $user_id]); // Xóa khỏi wishlist
      }

      // Thêm vào cart
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <section class="search-form">

      <form action="" method="POST">
         <input type="text" class="box" name="search_box" placeholder="search products...">
         <input type="submit" name="search_btn" value="search" class="btn">
      </form>

   </section>

   <?php



   ?>

   <section class="products" style="padding-top: 0; min-height:100vh;">

      <div class="box-container">

         <?php
         if (isset($_POST['search_btn'])) {
            $search_box = $_POST['search_box'];
            $search_box = htmlspecialchars($search_box, ENT_QUOTES, 'UTF-8');
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%' OR category LIKE '%{$search_box}%'");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <form action="" class="box" method="POST">
                     <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
                     <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                     <div class="name"><?= $fetch_products['name']; ?></div>
                     <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                     <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
                     <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
                     <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
                     <input type="number" min="1" value="1" name="p_qty" class="qty">
                     <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
                     <input type="submit" value="add to cart" class="btn" name="add_to_cart">
                  </form>
         <?php
               }
            } else {
               echo '<p class="empty">no result found!</p>';
            }
         };
         ?>

      </div>

   </section>






   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>