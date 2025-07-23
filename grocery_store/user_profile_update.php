<?php
// ========== TRANG CẬP NHẬT THÔNG TIN NGƯỜI DÙNG ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Kiểm tra đăng nhập - chuyển về login nếu chưa đăng nhập
if (!isset($user_id)) {
   header('location:login.php');
};

// ========== XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN ==========
if (isset($_POST['update_profile'])) {

   // Lấy và escape thông tin cá nhân từ form (sửa lỗi: dùng đúng biến)
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $name
   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

   // Cập nhật tên và email
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   // ========== XỬ LÝ CẬP NHẬT ẢNH ĐẠI DIỆN ==========
   $image = $_FILES['image']['name'];
   $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $image
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;
   $old_image = $_POST['old_image']; // Ảnh cũ để xóa

   if (!empty($image)) { // Nếu có upload ảnh mới
      if ($image_size > 2000000) { // Kiểm tra kích thước file (max 2MB)
         $message[] = 'image size is too large!';
      } else {
         // Cập nhật ảnh mới vào database
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $user_id]);
         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder); // Di chuyển file mới
            unlink('uploaded_img/' . $old_image); // Xóa ảnh cũ
            $message[] = 'image updated successfully!';
         };
      };
   };

   // ========== XỬ LÝ ĐỔI MẬT KHẨU ==========
   $old_pass = $_POST['old_pass']; // Mật khẩu cũ
   $update_pass = md5($_POST['update_pass']); // Mật khẩu mới (được mã hóa)
   $update_pass = htmlspecialchars($update_pass, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $update_pass
   $new_pass = md5($_POST['new_pass']); // Xác nhận mật khẩu mới
   $new_pass = htmlspecialchars($new_pass, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $new_pass
   $confirm_pass = md5($_POST['confirm_pass']); // Xác nhận lần 2
   $confirm_pass = htmlspecialchars($confirm_pass, ENT_QUOTES, 'UTF-8'); // Sửa lỗi: phải dùng $confirm_pass

   if (!empty($update_pass) and !empty($new_pass) and !empty($confirm_pass)) {
      if ($update_pass != $old_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $user_id]);
         $message[] = 'password updated successfully!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update user profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <section class="update-profile">

      <h1 class="title">update profile</h1>

      <form action="" method="POST" enctype="multipart/form-data">
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <div class="flex">
            <div class="inputBox">
               <span>username :</span>
               <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="update username" required class="box">
               <span>email :</span>
               <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="update email" required class="box">
               <span>update pic :</span>
               <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
               <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
            </div>
            <div class="inputBox">
               <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
               <span>old password :</span>
               <input type="password" name="update_pass" placeholder="enter previous password" class="box">
               <span>new password :</span>
               <input type="password" name="new_pass" placeholder="enter new password" class="box">
               <span>confirm password :</span>
               <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
            </div>
         </div>
         <div class="flex-btn">
            <input type="submit" class="btn" value="update profile" name="update_profile">
            <a href="home.php" class="option-btn">go back</a>
         </div>
      </form>

   </section>










   <?php include 'footer.php'; ?>


   <script src="js/script.js"></script>

</body>

</html>