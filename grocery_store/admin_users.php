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

// Xử lý xóa người dùng khi có tham số delete trong URL
if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete']; // Lấy ID người dùng cần xóa

   // Xóa người dùng khỏi database
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);

   // Chuyển hướng về trang admin_users.php sau khi xóa
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="user-accounts">

      <h1 class="title">user accounts</h1>

      <div class="box-container">

         <?php
         // Truy vấn lấy tất cả người dùng từ database
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();

         // Lặp qua từng người dùng và hiển thị thông tin
         while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
         ?>
            <!-- Ẩn thông tin admin hiện tại để không thể tự xóa chính mình -->
            <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) {
                                       echo 'display:none';
                                    }; ?>">
               <!-- Hiển thị avatar người dùng -->
               <img src="uploaded_img/<?= htmlspecialchars($fetch_users['image']); ?>" alt="">

               <!-- Hiển thị thông tin người dùng với htmlspecialchars để bảo mật -->
               <p> user id : <span><?= htmlspecialchars($fetch_users['id']); ?></span></p>
               <p> username : <span><?= htmlspecialchars($fetch_users['name']); ?></span></p>
               <p> email : <span><?= htmlspecialchars($fetch_users['email']); ?></span></p>

               <!-- Hiển thị loại người dùng, admin sẽ có màu cam -->
               <p> user type : <span style=" color:<?php if ($fetch_users['user_type'] == 'admin') {
                                                      echo 'orange';
                                                   }; ?>"><?= htmlspecialchars($fetch_users['user_type']); ?></span></p>

               <!-- Nút xóa người dùng với xác nhận trước khi xóa -->
               <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
            </div>
         <?php
         }
         ?>
      </div>

   </section>













   <script src="js/script.js"></script>

</body>

</html>