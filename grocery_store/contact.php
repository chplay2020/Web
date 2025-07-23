<?php
// ========== TRANG LIÊN HỆ - GỬI TIN NHẮN CHO ADMIN ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
};

// ========== XỬ LÝ GỬI TIN NHẮN LIÊN HỆ ==========
if (isset($_POST['send'])) {

   // Lấy và escape dữ liệu từ form
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
   $number = $_POST['number'];
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
   $msg = $_POST['msg'];
   $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');

   // Kiểm tra tin nhắn đã được gửi chưa (tránh spam)
   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if ($select_message->rowCount() > 0) {
      $message[] = 'already sent message!'; // Tin nhắn đã được gửi trước đó
   } else {

      // Thêm tin nhắn mới vào database
      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!'; // Gửi thành công

   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <section class="contact">

      <h1 class="title">get in touch</h1>

      <form action="" method="POST">
         <input type="text" name="name" class="box" required placeholder="enter your name">
         <input type="email" name="email" class="box" required placeholder="enter your email">
         <input type="number" name="number" min="0" class="box" required placeholder="enter your number">
         <textarea name="msg" class="box" required placeholder="enter your message" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" class="btn" name="send">
      </form>

   </section>








   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>