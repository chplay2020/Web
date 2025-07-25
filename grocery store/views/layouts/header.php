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

// Get database connection for header queries
$conn = Database::getInstance()->getConnection();
?>

<header class="header">

   <div class="flex">

      <a href="/" class="logo">Grocer<span>.</span></a>

      <nav class="navbar">
         <a href="/">home</a>
         <a href="about">about</a>
         <a href="shop">shop</a>
         <a href="contact">contact</a>
         <a href="orders">orders</a>
      </nav>

      <div class="icons">
         <?php
         $user_id = $_SESSION['user_id'] ?? null;
         if ($user_id) {
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         } else {
            $total_wishlist_counts = 0;
            $total_cart_counts = 0;
         }
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <a href="wishlist"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         if ($user_id) {
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
               <p><?= $fetch_profile["name"]; ?></p>
               <a href="user_profile_update" class="btn">update profile</a>
               <div class="flex-btn">
                  <a href="register" class="option-btn">register</a>
                  <a href="login" class="option-btn">login</a>
               </div>
               <a href="logout" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
         <?php
            }
         } else {
         ?>
            <p>please login first!</p>
            <div class="flex-btn">
               <a href="register" class="option-btn">register</a>
               <a href="login" class="option-btn">login</a>
            </div>
         <?php
         }
         ?>
      </div>

   </div>

   <form action="search_page" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" value="<?= isset($searchBox) ? $searchBox : ''; ?>">
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

</header>
