<?php
// Kết nối file cấu hình database
@include 'config.php';

// Khởi tạo session để quản lý phiên đăng nhập
session_start();

// Lấy ID của admin từ session
$admin_id = $_SESSION['admin_id'];

// Kiểm tra xem admin đã đăng nhập chưa, nếu chưa thì chuyển về trang login
if (!isset($admin_id)) {
   header('location:login.php');
};

// Xử lý cập nhật trạng thái thanh toán của đơn hàng
if (isset($_POST['update_order'])) {

   $order_id = $_POST['order_id']; // Lấy ID đơn hàng cần cập nhật
   $update_payment = $_POST['update_payment']; // Lấy trạng thái thanh toán mới

   // Bảo mật dữ liệu đầu vào để tránh XSS attacks
   $update_payment = htmlspecialchars($update_payment, ENT_QUOTES, 'UTF-8');

   // Cập nhật trạng thái thanh toán trong database
   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'payment has been updated!'; // Thông báo cập nhật thành công
};

// Xử lý xóa đơn hàng khi có tham số delete trong URL
if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete']; // Lấy ID đơn hàng cần xóa

   // Xóa đơn hàng khỏi database
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);

   // Chuyển hướng về trang admin_orders.php sau khi xóa
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="placed-orders">

      <h1 class="title">placed orders</h1>

      <div class="box-container">

         <?php
         // Truy vấn lấy tất cả đơn hàng từ database
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();

         // Kiểm tra có đơn hàng nào không
         if ($select_orders->rowCount() > 0) {
            // Lặp qua từng đơn hàng và hiển thị thông tin
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <!-- Hiển thị các thông tin đơn hàng với htmlspecialchars để bảo mật -->
                  <p> user id : <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span> </p>
                  <p> placed on : <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span> </p>
                  <p> name : <span><?= htmlspecialchars($fetch_orders['name']); ?></span> </p>
                  <p> email : <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
                  <p> number : <span><?= htmlspecialchars($fetch_orders['number']); ?></span> </p>
                  <p> address : <span><?= htmlspecialchars($fetch_orders['address']); ?></span> </p>
                  <p> total products : <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span> </p>
                  <p> total price : <span>$<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span> </p>
                  <p> payment method : <span><?= htmlspecialchars($fetch_orders['method']); ?></span> </p>

                  <!-- Form cập nhật trạng thái thanh toán -->
                  <form action="" method="POST">
                     <!-- Hidden input chứa ID đơn hàng -->
                     <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">

                     <!-- Dropdown chọn trạng thái thanh toán mới -->
                     <select name="update_payment" class="drop-down">
                        <option value="" selected disabled><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                     </select>

                     <div class="flex-btn">
                        <!-- Nút cập nhật trạng thái -->
                        <input type="submit" name="update_order" class="option-btn" value="udate">
                        <!-- Nút xóa đơn hàng với xác nhận -->
                        <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            // Hiển thị thông báo nếu chưa có đơn hàng nào
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>

      </div>

   </section>












   <script src="js/script.js"></script>

</body>

</html>