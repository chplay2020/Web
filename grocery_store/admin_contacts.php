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

// Xử lý xóa tin nhắn liên hệ khi có tham số delete trong URL
if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete']; // Lấy ID tin nhắn cần xóa

   // Xóa tin nhắn khỏi database
   $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
   $delete_message->execute([$delete_id]);

   // Chuyển hướng về trang admin_contacts.php sau khi xóa
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="messages">

      <h1 class="title">messages</h1>

      <div class="box-container">

         <?php
         // Truy vấn lấy tất cả tin nhắn liên hệ từ database
         $select_message = $conn->prepare("SELECT * FROM `message`");
         $select_message->execute();

         // Kiểm tra có tin nhắn nào không
         if ($select_message->rowCount() > 0) {
            // Lặp qua từng tin nhắn và hiển thị
            while ($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <!-- Hiển thị thông tin tin nhắn với htmlspecialchars để bảo mật -->
                  <p> user id : <span><?= htmlspecialchars($fetch_message['user_id']); ?></span> </p>
                  <p> name : <span><?= htmlspecialchars($fetch_message['name']); ?></span> </p>
                  <p> number : <span><?= htmlspecialchars($fetch_message['number']); ?></span> </p>
                  <p> email : <span><?= htmlspecialchars($fetch_message['email']); ?></span> </p>
                  <p> message : <span><?= htmlspecialchars($fetch_message['message']); ?></span> </p>

                  <!-- Nút xóa tin nhắn với xác nhận trước khi xóa -->
                  <a href="admin_contacts.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
               </div>
         <?php
            }
         } else {
            // Hiển thị thông báo nếu chưa có tin nhắn nào
            echo '<p class="empty">you have no messages!</p>';
         }
         ?>

      </div>

   </section>













   <script src="js/script.js"></script>

</body>

</html>