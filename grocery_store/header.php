<?php
// -- Chú thích ngắn gọn cho header.php --

// Nếu có biến $message (thường chứa thông báo cho người dùng), thì hiển thị nó.
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">
   <div class="flex">
      <!-- Logo trang web -->
      <a href="admin_page.php" class="logo">Groco<span>.</span></a>

      <!-- Thanh điều hướng chính -->
      <nav class="navbar">
         <a href="home.php">home</a>
         <a href="shop.php">shop</a>
         <a href="orders.php">orders</a>
         <a href="about.php">about</a>
         <a href="contact.php">contact</a>
      </nav>

      <!-- Các icon chức năng: menu, user, tìm kiếm, danh sách yêu thích, giỏ hàng -->
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <a href="search_page.php" class="fas fa-search"></a>
         <?php
         // Đếm số lượng sản phẩm trong giỏ hàng và danh sách yêu thích của người dùng hiện tại.
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         ?>
         <!-- Hiển thị số lượng sản phẩm -->
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <!-- Hồ sơ người dùng (profile) -->
      <div class="profile">
         <?php
         // Lấy thông tin người dùng từ CSDL để hiển thị.
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <!-- Nút đăng nhập/đăng ký cho khách -->
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>
   </div>
</header>