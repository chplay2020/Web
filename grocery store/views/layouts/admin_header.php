<?php
if (isset($message)) {
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

      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_page.php">home</a>
         <a href="admin_products.php">products</a>
         <a href="admin_orders.php">orders</a>
         <a href="admin_users.php">users</a>
         <a href="admin_contacts.php">messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
      <?php
      $adminId = $_SESSION['admin_id'] ?? null;
      if ($adminId) {
      require_once __DIR__ . '/../../core/Database.php';
      $conn = Database::getInstance()->getConnection();
      $selectProfile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $selectProfile->execute([$adminId]);
         $fetchProfile = $selectProfile->fetch(PDO::FETCH_ASSOC);
             if ($fetchProfile) {
          ?>
               <img src="uploaded_img/<?= $fetchProfile['image']; ?>" alt="">
               <p><?= $fetchProfile['name']; ?></p>
               <a href="/admin_update_profile" class="btn">update profile</a>
               <a href="/logout" class="delete-btn">logout</a>
         <?php
            }
         }
         ?>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</header>
