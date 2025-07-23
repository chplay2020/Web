<?php
// ========== TRANG THANH TOÁN - XỬ LÝ ĐẶT HÀNG ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
};

// ========== XỬ LÝ ĐẶT HÀNG ==========
if (isset($_POST['order'])) {

   // Lấy và escape thông tin khách hàng từ form
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $name thay vì $email
   $number = $_POST['number'];
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $number
   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
   $method = $_POST['method']; // Phương thức thanh toán
   $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $method

   // Ghép địa chỉ từ các trường input
   $address = 'flat no. ' . $_POST['flat'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $address
   $placed_on = date('d-M-Y'); // Ngày đặt hàng

   $cart_total = 0; // Tổng tiền
   $cart_products[] = ''; // Mảng chứa danh sách sản phẩm

   // Lấy tất cả sản phẩm trong cart của user
   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if ($cart_query->rowCount() > 0) {
      while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
         // Tạo chuỗi thông tin sản phẩm (tên + số lượng)
         $cart_products[] = $cart_item['name'] . ' ( ' . $cart_item['quantity'] . ' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']); // Tính tiền cho từng sản phẩm
         $cart_total += $sub_total; // Cộng dồn vào tổng tiền
      };
   };

   $total_products = implode(', ', $cart_products); // Ghép danh sách sản phẩm thành chuỗi

   // Kiểm tra đơn hàng đã tồn tại chưa (tránh trùng lặp)
   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if ($cart_total == 0) {
      $message[] = 'your cart is empty'; // Giỏ hàng trống
   } elseif ($order_query->rowCount() > 0) {
      $message[] = 'order placed already!'; // Đơn hàng đã tồn tại
   } else {
      // Thêm đơn hàng mới vào database
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'order placed successfully!';
   }
}

?>
<!-- ========== PHẦN HTML - GIAO DIỆN TRANG THANH TOÁN ========== -->
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?> <!-- Include header -->

   <!-- ========== SECTION HIỂN THỊ ĐƠN HÀNG ========== -->
   <section class="display-orders">

      <?php
      $cart_grand_total = 0; // Tổng tiền của đơn hàng
      // Lấy tất cả sản phẩm trong cart để hiển thị
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if ($select_cart_items->rowCount() > 0) {
         while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']); // Tính tiền từng sản phẩm
            $cart_grand_total += $cart_total_price; // Cộng dồn vào tổng tiền
      ?>
            <!-- Hiển thị từng sản phẩm với giá và số lượng -->
            <p> <?= $fetch_cart_items['name']; ?> <span>(<?= '$' . $fetch_cart_items['price'] . '/- x ' . $fetch_cart_items['quantity']; ?>)</span> </p>
      <?php
         }
      } else {
         echo '<p class="empty">your cart is empty!</p>'; // Thông báo giỏ hàng trống
      }
      ?>
      <div class="grand-total">grand total : <span>$<?= $cart_grand_total; ?>/-</span></div> <!-- Hiển thị tổng tiền -->
   </section>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>place your order</h3>

         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" required>
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="number" name="number" placeholder="enter your number" class="box" required>
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" required>
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paytm">paytm</option>
                  <option value="paypal">paypal</option>
               </select>
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
            </div>
            <div class="inputBox">
               <span>address line 02 :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" required>
            </div>
            <div class="inputBox">
               <span>city :</span>
               <input type="text" name="city" placeholder="e.g. mumbai" class="box" required>
            </div>
            <div class="inputBox">
               <span>state :</span>
               <input type="text" name="state" placeholder="e.g. maharashtra" class="box" required>
            </div>
            <div class="inputBox">
               <span>country :</span>
               <input type="text" name="country" placeholder="e.g. India" class="box" required>
            </div>
            <div class="inputBox">
               <span>pin code :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
            </div>
         </div>

         <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1) ? '' : 'disabled'; ?>" value="place order">

      </form>

   </section>








   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>