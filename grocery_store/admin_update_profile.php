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

// Xử lý cập nhật thông tin admin khi form được submit
if (isset($_POST['update_profile'])) {

   // Lấy và bảo mật thông tin cơ bản
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Tên admin

   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); // Email admin

   // Cập nhật tên và email
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $admin_id]);

   // Xử lý cập nhật ảnh đại diện
   $image = $_FILES['image']['name'];
   $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
   $image_size = $_FILES['image']['size']; // Kích thước ảnh
   $image_tmp_name = $_FILES['image']['tmp_name']; // Đường dẫn tạm
   $image_folder = 'uploaded_img/' . $image; // Thư mục lưu ảnh
   $old_image = $_POST['old_image']; // Ảnh cũ

   // Kiểm tra có ảnh mới được upload không
   if (!empty($image)) {
      // Kiểm tra kích thước ảnh (tối đa 2MB)
      if ($image_size > 2000000) {
         $message[] = 'image size is too large!';
      } else {
         // Cập nhật ảnh mới trong database
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $admin_id]);
         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder); // Lưu ảnh mới
            unlink('uploaded_img/' . $old_image); // Xóa ảnh cũ
            $message[] = 'image updated successfully!';
         };
      };
   };

   // Xử lý cập nhật mật khẩu
   $old_pass = $_POST['old_pass']; // Mật khẩu cũ
   $update_pass = md5($_POST['update_pass']); // Mật khẩu hiện tại (để xác thực)
   $update_pass = htmlspecialchars($update_pass, ENT_QUOTES, 'UTF-8');

   $new_pass = md5($_POST['new_pass']); // Mật khẩu mới
   $new_pass = htmlspecialchars($new_pass, ENT_QUOTES, 'UTF-8');

   $confirm_pass = md5($_POST['confirm_pass']); // Xác nhận mật khẩu mới
   $confirm_pass = htmlspecialchars($confirm_pass, ENT_QUOTES, 'UTF-8');

   // Kiểm tra và cập nhật mật khẩu nếu có thay đổi
   if (!empty($update_pass) and !empty($new_pass) and !empty($confirm_pass)) {
      // Kiểm tra mật khẩu cũ có đúng không
      if ($update_pass != $old_pass) {
         $message[] = 'old password not matched!';
      }
      // Kiểm tra mật khẩu mới và xác nhận có khớp không
      elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         // Cập nhật mật khẩu mới
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $admin_id]);
         $message[] = 'password updated successfully!';
      }
   }
} // Kết thúc xử lý cập nhật profile

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update admin profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="update-profile">

      <h1 class="title">update profile</h1>

      <?php
      // Truy vấn lấy thông tin profile admin hiện tại
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_profile->execute([$admin_id]);
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>

      <!-- Form cập nhật thông tin admin -->
      <form action="" method="POST" enctype="multipart/form-data">
         <!-- Hiển thị ảnh đại diện hiện tại -->
         <img src="uploaded_img/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="">

         <div class="flex">
            <div class="inputBox">
               <span>username :</span>
               <!-- Input để cập nhật tên admin -->
               <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>" placeholder="update username" required class="box">
               <span>email :</span>
               <!-- Input để cập nhật email admin -->
               <input type="email" name="email" value="<?= htmlspecialchars($fetch_profile['email']); ?>" placeholder="update email" required class="box">

               <span>update pic :</span>
               <!-- Input để upload ảnh đại diện mới -->
               <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
               <!-- Hidden input lưu ảnh cũ -->
               <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_profile['image']); ?>">
            </div>
            <div class="inputBox">
               <!-- Hidden input lưu mật khẩu cũ để so sánh -->
               <input type="hidden" name="old_pass" value="<?= htmlspecialchars($fetch_profile['password']); ?>">

               <span>old password :</span>
               <!-- Input để nhập mật khẩu cũ -->
               <input type="password" name="update_pass" placeholder="enter previous password" class="box">

               <span>new password :</span>
               <!-- Input để nhập mật khẩu mới -->
               <input type="password" name="new_pass" placeholder="enter new password" class="box">

               <span>confirm password :</span>
               <!-- Input để xác nhận mật khẩu mới -->
               <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
            </div>
         </div>
         <div class="flex-btn">
            <!-- Nút cập nhật thông tin -->
            <input type="submit" class="btn" value="update profile" name="update_profile">
            <!-- Nút quay lại trang admin -->
            <a href="admin_page.php" class="option-btn">go back</a>
         </div>
      </form>

   </section>













   <script src="js/script.js"></script>

</body>

</html>