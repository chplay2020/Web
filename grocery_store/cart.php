<?php
// ========== TRANG GIỎ HÀNG - QUẢN LÝ SẢN PHẨM TRONG CART ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
};

// ========== XỬ LÝ XÓA SẢN PHẨM KHỎI GIỎ HÀNG ==========
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete']; // Lấy ID sản phẩm cần xóa từ URL
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_id]); // Xóa sản phẩm khỏi cart
   header('location:cart.php'); // Reload trang
}

// ========== XỬ LÝ XÓA TẤT CẢ SẢN PHẨM KHỎI GIỎ HÀNG ==========
if (isset($_GET['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]); // Xóa tất cả sản phẩm của user
   header('location:cart.php'); // Reload trang
}

// ========== XỬ LÝ CẬP NHẬT SỐ LƯỢNG SẢN PHẨM ==========
if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id']; // ID của item trong cart
   $p_qty = $_POST['p_qty']; // Số lượng mới
   $p_qty = htmlspecialchars($p_qty, ENT_QUOTES, 'UTF-8'); // Escape để bảo mật
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$p_qty, $cart_id]); // Cập nhật số lượng
   $message[] = 'cart quantity updated'; // Thông báo thành công
}

?>
<!-- ========== PHẦN HTML - GIAO DIỆN GIỎ HÀNG ========== -->
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?> <!-- Include header -->

   <!-- ========== SECTION HIỂN THỊ GIỎ HÀNG ========== -->
   <section class="shopping-cart">

      <h1 class="title">products added</h1>

      <div class="box-container">

         <?php
         $grand_total = 0; // Tổng tiền của tất cả sản phẩm
         // Lấy tất cả sản phẩm trong cart của user
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            // Lặp qua từng sản phẩm trong cart
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <!-- Form cho mỗi sản phẩm trong cart -->
               <form action="" method="POST" class="box">
                  <!-- Nút xóa sản phẩm với xác nhận -->
                  <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                  <!-- Link xem chi tiết sản phẩm -->
                  <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt=""> <!-- Ảnh sản phẩm -->
                  <div class="name"><?= $fetch_cart['name']; ?></div> <!-- Tên sản phẩm -->
                  <div class="price">$<?= $fetch_cart['price']; ?>/-</div> <!-- Giá sản phẩm -->
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>"> <!-- ID cart item -->
                  <div class="flex-btn">
                     <!-- Input cập nhật số lượng -->
                     <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
                     <!-- Nút cập nhật số lượng -->
                     <input type="submit" value="update" name="update_qty" class="option-btn">
                  </div>
                  <!-- Hiển thị tổng tiền của sản phẩm này -->
                  <div class="sub-total"> sub total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
               </form>
         <?php
               $grand_total += $sub_total; // Cộng dồn vào tổng tiền
            }
         } else {
            echo '<p class="empty">your cart is empty</p>'; // Thông báo giỏ hàng trống
         }
         ?>
      </div>

      <!-- ========== PHẦN TỔNG TIỀN VÀ CÁC HÀNH ĐỘNG ========== -->
      <div class="cart-total">
         <p>grand total : <span>$<?= $grand_total; ?>/-</span></p> <!-- Tổng tiền -->
         <a href="shop.php" class="option-btn">continue shopping</a> <!-- Tiếp tục mua sắm -->
         <!-- Nút xóa tất cả (disabled nếu giỏ hàng trống) -->
         <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">delete all</a>
         <!-- Nút thanh toán (disabled nếu giỏ hàng trống) -->
         <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
      </div>

   </section>

   <?php include 'footer.php'; ?> <!-- Include footer -->

   <script src="js/script.js"></script> <!-- Include JavaScript -->

</body>

</html>

</body>

</html>