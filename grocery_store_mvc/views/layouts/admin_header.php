<?php
// Hiển thị thông báo hệ thống (success, error, warning)
// Kiểm tra xem có biến $message được truyền từ controller không
if (isset($message)) {
   // Lặp qua từng thông báo và hiển thị
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="flex">

      <!-- Logo admin panel - click để về trang chủ admin -->
      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

      <!-- Menu điều hướng chính của admin -->
      <nav class="navbar">
         <a href="admin_page.php">home</a> <!-- Dashboard -->
         <a href="admin_products.php">products</a> <!-- Quản lý sản phẩm -->
         <a href="admin_orders.php">orders</a> <!-- Quản lý đơn hàng -->
         <a href="admin_users.php">users</a> <!-- Quản lý người dùng -->
         <a href="admin_contacts.php">messages</a> <!-- Tin nhắn khách hàng -->
         <a href="sync_database.php">sync DB</a> <!-- Đồng bộ database -->
      </nav>

      <!-- Icons cho mobile menu và user profile -->
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div> <!-- Hamburger menu cho mobile -->
         <div id="user-btn" class="fas fa-user"></div> <!-- Icon user profile -->
      </div>

      <!-- Profile dropdown -->
      <div class="profile">
         <?php
         // Lấy ID admin từ session
         $adminId = $_SESSION['admin_id'] ?? null;

         // Nếu admin đã đăng nhập
         if ($adminId) {
            // Kết nối database sử dụng Singleton pattern
            require_once __DIR__ . '/../../core/Database.php';
            $conn = Database::getInstance()->getConnection();

            // Truy vấn thông tin admin từ bảng users
            $selectProfile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $selectProfile->execute([$adminId]);
            $fetchProfile = $selectProfile->fetch(PDO::FETCH_ASSOC);

            // Nếu tìm thấy thông tin admin
            if ($fetchProfile) {
         ?>
               <!-- Hiển thị avatar và thông tin admin -->
               <img src="uploaded_img/<?= $fetchProfile['image']; ?>" alt="">
               <p><?= $fetchProfile['name']; ?></p>
               <a href="/admin_update_profile" class="btn">update profile</a>
               <a href="/logout" class="delete-btn">logout</a>
         <?php
            }
         }
         ?>
         <!-- BUG: Phần này luôn hiển thị dù admin đã đăng nhập -->
         <!-- TODO: Cần di chuyển vào else statement -->
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</header>